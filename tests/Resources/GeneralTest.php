<?php

declare(strict_types=1);

it('checks api availability', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Result' => 'OK']);

    $result = $factory->preauthorizedClient()->general()->check();

    expect($result)->toBe(['Result' => 'OK']);
    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/general/check');
    expect($factory->lastRequest()->getHeaderLine('Authorization'))->toBe('Bearer cached-token');
});
