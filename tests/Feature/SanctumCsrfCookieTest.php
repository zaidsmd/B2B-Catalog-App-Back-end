<?php

it('can access the sanctum csrf-cookie route', function () {
    $response = $this->getJson('/api/sanctum/csrf-cookie');

    $response->assertNoContent();
});

it('can access the default sanctum csrf-cookie route', function () {
    $response = $this->get('/sanctum/csrf-cookie');

    $response->assertNoContent();
});
