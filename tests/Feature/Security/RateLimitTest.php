<?php

it('has security/ratelimit page', function () {
    $response = $this->get('/security/ratelimit');

    $response->assertStatus(200);
});
