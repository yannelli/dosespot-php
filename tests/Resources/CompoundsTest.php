<?php

declare(strict_types=1);

it('searches compounds by name', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->compounds()->search(name: 'Acetaminophen');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/compounds/search');
    expect($uri)->toContain('name=Acetaminophen');
    expect($uri)->not->toContain('ndc=');
});

it('searches compounds by ndc', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->compounds()->search(ndc: '12345-678-90');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/compounds/search');
    expect($uri)->toContain('ndc=12345-678-90');
    expect($uri)->not->toContain('name=');
});

it('searches compounds with both name and ndc', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->compounds()->search(
        name: 'Ibuprofen',
        ndc: '98765-432-10',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('name=Ibuprofen');
    expect($uri)->toContain('ndc=98765-432-10');
});

it('searches compounds with no filters and omits null query params', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->compounds()->search();

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('api/compounds/search');
    expect($uri)->not->toContain('name=');
    expect($uri)->not->toContain('ndc=');
});
