<?php

it('has security/idor page', function () {
    $response = $this->get('/security/idor');

    $response->assertStatus(200);
});
