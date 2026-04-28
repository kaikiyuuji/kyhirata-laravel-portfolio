<?php

it('has security/xss page', function () {
    $response = $this->get('/security/xss');

    $response->assertStatus(200);
});
