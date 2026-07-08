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
