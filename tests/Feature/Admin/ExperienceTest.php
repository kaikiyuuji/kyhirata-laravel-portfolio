<?php

it('has admin/experience page', function () {
    $response = $this->get('/admin/experience');

    $response->assertStatus(200);
});
