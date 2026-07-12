<?php

declare(strict_types=1);

it('searches supplies by name', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->supplies()->search(name: 'lancet');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/supplies/search');
    expect($uri)->toContain('name=lancet');
    expect($uri)->not->toContain('NDC=');
});

it('searches supplies by NDC', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->supplies()->search(ndc: '12345-678-90');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/supplies/search');
    expect($uri)->toContain('NDC=12345-678-90');
    expect($uri)->not->toContain('name=');
});

it('searches supplies with both name and NDC', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->supplies()->search(
        name: 'lancet',
        ndc: '12345-678-90',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('name=lancet');
    expect($uri)->toContain('NDC=12345-678-90');
});

it('searches supplies with no filters and omits null query params', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->supplies()->search();

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/supplies/search');
    expect($uri)->not->toContain('name=');
    expect($uri)->not->toContain('NDC=');
});
