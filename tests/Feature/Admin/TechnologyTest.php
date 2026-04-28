<?php

it('has admin/technology page', function () {
    $response = $this->get('/admin/technology');

    $response->assertStatus(200);
});
