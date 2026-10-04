<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Resources\Resource;

/**
 * @return list<array{class: class-string, method: string, operationId: string}>
 */
function fullEpcsResourceMethods(): array
{
    $cases = [];

    foreach (glob(dirname(__DIR__, 2).'/src/Resources/*.php') as $file) {
        $class = 'Yannelli\\DoseSpot\\Resources\\'.basename($file, '.php');

        if (! class_exists($class)) {
            continue;
        }

        $reflection = new ReflectionClass($class);

        if ($reflection->isAbstract() || ! $reflection->isSubclassOf(Resource::class)) {
            continue;
        }

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== $class) {
                continue;
            }

            $doc = $method->getDocComment() ?: '';

            if (! preg_match('/@dosespot\s+(\S+)/', $doc, $match)) {
                throw new RuntimeException($class.'::'.$method->getName().' is missing an @dosespot operation id.');
            }

            $cases[] = [
                'class' => $class,
                'method' => $method->getName(),
                'operationId' => $match[1],
            ];
        }
    }

    return $cases;
}

/**
 * @return array<string, array{method: string, path: string}>
 */
function fullEpcsFixtureOperations(): array
{
    $fixture = json_decode(
        (string) file_get_contents(dirname(__DIR__).'/Fixtures/full-epcs-v2-operations.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    $operations = [];

    foreach ($fixture['operations'] as $operation) {
        $operations[$operation['operationId']] = [
            'method' => $operation['method'],
            'path' => $operation['path'],
        ];
    }

    return $operations;
}

it('documents every Full + EPCS v2 operation exactly once', function () {
    $expected = fullEpcsFixtureOperations();
    $actual = [];

    foreach (fullEpcsResourceMethods() as $case) {
        $method = new ReflectionMethod($case['class'], $case['method']);
        $doc = (string) $method->getDocComment();

        expect($doc)->toMatch('/(GET|POST|PUT|PATCH|DELETE) \/api\/\S+/');
        preg_match('/(GET|POST|PUT|PATCH|DELETE) (\/api\/\S+)/', $doc, $documented);

        $actual[$case['operationId']] = $documented[1].' '.$documented[2];
    }

    expect(array_keys($actual))->toEqualCanonicalizing(array_keys($expected));

    $expectedLines = [];

    foreach ($expected as $operationId => $operation) {
        $expectedLines[$operationId] = $operation['method'].' '.$operation['path'];
    }

    ksort($actual);
    ksort($expectedLines);

    expect($actual)->toBe($expectedLines);
});

it('calls the documented Full + EPCS v2 endpoint', function (string $class, string $methodName) {
    $factory = factory();
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK'], 'Id' => 1]);

    $method = new ReflectionMethod($class, $methodName);
    $source = methodSource($method);
    $args = argumentsFor($method, $source);

    $resource = new $class($factory->preauthorizedClient()->http);
    $method->invokeArgs($resource, $args);

    $request = $factory->lastRequest();
    $doc = (string) $method->getDocComment();
    preg_match('/(GET|POST|PUT|PATCH|DELETE) /', $doc, $documented);

    expect($request->getMethod())->toBe($documented[1]);
    expect($request->getUri()->getPath())->toBe('/webapi/v2/'.resolvePath($source, $method, $args));
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer cached-token');
    expect($request->getHeaderLine('Subscription-Key'))->toBe('subscription-key');

    if ($documented[1] !== 'GET') {
        return;
    }

    foreach ($method->getParameters() as $index => $parameter) {
        $value = $args[$index];
        $name = $parameter->getName();

        if ($value === null || is_array($value)) {
            continue;
        }

        if (preg_match_all("/'([^']+)'\\s*=>\\s*\\$$name\\b/", $source, $keys)) {
            foreach ($keys[1] as $key) {
                expect($request->getUri()->getQuery())->toContain(rawurlencode($key).'='.rawurlencode(scalarQueryValue($value)));
            }
        }
    }
})->with(function () {
    foreach (fullEpcsResourceMethods() as $case) {
        yield $case['operationId'] => [$case['class'], $case['method']];
    }
});

function methodSource(ReflectionMethod $method): string
{
    $lines = file($method->getFileName());

    return implode('', array_slice($lines, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
}

function argumentsFor(ReflectionMethod $method, string $source): array
{
    $args = [];
    $ints = 0;

    foreach ($method->getParameters() as $parameter) {
        $name = $parameter->getName();
        $inPath = str_contains($source, '{$'.$name.'}');

        if (! $inPath && $parameter->isDefaultValueAvailable()) {
            $args[] = $parameter->getDefaultValue();

            continue;
        }

        $types = parameterTypeNames($parameter);

        if (in_array('array', $types, true)) {
            $args[] = ['Example' => true];

            continue;
        }

        if (in_array('float', $types, true)) {
            $args[] = 1.5;

            continue;
        }

        if (in_array('bool', $types, true)) {
            $args[] = true;

            continue;
        }

        if (in_array('int', $types, true)) {
            $ints++;
            $args[] = 10 + $ints;

            continue;
        }

        $args[] = $name;
    }

    return $args;
}

function parameterTypeNames(ReflectionParameter $parameter): array
{
    $type = $parameter->getType();

    if ($type instanceof ReflectionUnionType) {
        return array_map(static fn (ReflectionNamedType $named): string => $named->getName(), $type->getTypes());
    }

    if ($type instanceof ReflectionNamedType) {
        return [$type->getName()];
    }

    return [];
}

function resolvePath(string $source, ReflectionMethod $method, array $args): string
{
    if (! preg_match('/return \$this->(?:get|post|put|patch|delete)\(\s*([\'"])(.*?)\\1/s', $source, $match)) {
        throw new RuntimeException('No request path found in '.$method->getDeclaringClass()->getName().'::'.$method->getName());
    }

    $path = $match[2];

    foreach ($method->getParameters() as $index => $parameter) {
        $value = $args[$index];

        if (is_array($value)) {
            continue;
        }

        $path = str_replace('{$'.$parameter->getName().'}', (string) $value, $path);
    }

    return $path;
}

function scalarQueryValue(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return (string) $value;
}
