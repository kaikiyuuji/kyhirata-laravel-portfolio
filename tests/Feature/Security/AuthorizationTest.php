<?php

it('has security/authorization page', function () {
    $response = $this->get('/security/authorization');

    $response->assertStatus(200);
});
