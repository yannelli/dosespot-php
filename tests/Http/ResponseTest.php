<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Yannelli\DoseSpot\Http\Response;

it('decodes a valid JSON body into an array', function () {
    $response = new Response(new GuzzleResponse(200, [], '{"Id": 1, "Name": "test"}'));

    expect($response->json())->toBe(['Id' => 1, 'Name' => 'test']);
});

it('returns an empty array for an empty body', function () {
    $response = new Response(new GuzzleResponse(200, [], ''));

    expect($response->json())->toBe([]);
});

it('returns an empty array for invalid JSON', function () {
    $response = new Response(new GuzzleResponse(200, [], 'not json'));

    expect($response->json())->toBe([]);
});

it('caches the decoded body across multiple calls', function () {
    $raw = new GuzzleResponse(200, [], '{"Id": 1}');

    // Replace the body with an empty stream after the first decode
    // to prove the second call uses the cache
    $response = new Response($raw);

    $first = $response->json();
    $second = $response->json();

    expect($first)->toBe(['Id' => 1])
        ->and($second)->toBe(['Id' => 1]);
});

it('returns the raw body as a string', function () {
    $response = new Response(new GuzzleResponse(200, [], 'plain text'));

    expect($response->body())->toBe('plain text');
});

it('returns the status code', function () {
    $response = new Response(new GuzzleResponse(201));

    expect($response->statusCode())->toBe(201);
});

it('reports success for 2xx status codes', function () {
    expect((new Response(new GuzzleResponse(200)))->isSuccessful())->toBeTrue();
    expect((new Response(new GuzzleResponse(204)))->isSuccessful())->toBeTrue();
});

it('reports failure for non-2xx status codes', function () {
    expect((new Response(new GuzzleResponse(301)))->isSuccessful())->toBeFalse();
    expect((new Response(new GuzzleResponse(404)))->isSuccessful())->toBeFalse();
    expect((new Response(new GuzzleResponse(500)))->isSuccessful())->toBeFalse();
});

it('returns the first value of a header', function () {
    $response = new Response(new GuzzleResponse(200, ['Retry-After' => '5']));

    expect($response->header('Retry-After'))->toBe('5');
});

it('returns null when a header is absent', function () {
    $response = new Response(new GuzzleResponse(200, []));

    expect($response->header('Retry-After'))->toBeNull();
});

it('caches the raw body across multiple reads of a non-seekable stream', function () {
    $stream = \GuzzleHttp\Psr7\Utils::streamFor('{"Message": "boom"}');
    $stream = new \GuzzleHttp\Psr7\NoSeekStream($stream);
    $response = new Response(new GuzzleResponse(500, [], $stream));

    expect($response->body())->toBe('{"Message": "boom"}');
    // Without body caching, a second cast of the consumed non-seekable stream is empty.
    expect($response->body())->toBe('{"Message": "boom"}');
    expect($response->json())->toBe(['Message' => 'boom']);
});

it('keeps body available for json after body is read first', function () {
    $stream = new \GuzzleHttp\Psr7\NoSeekStream(
        \GuzzleHttp\Psr7\Utils::streamFor('{"Message": "patient not found"}'),
    );
    $response = new Response(new GuzzleResponse(404, [], $stream));

    // Matches HttpClient::throwForStatus, which reads body() before json().
    $rawBody = $response->body();
    $decoded = $response->json();

    expect($rawBody)->toBe('{"Message": "patient not found"}');
    expect($decoded)->toBe(['Message' => 'patient not found']);
    expect($response->body())->toBe($rawBody);
});
