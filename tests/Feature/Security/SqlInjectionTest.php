<?php

it('has security/sqlinjection page', function () {
    $response = $this->get('/security/sqlinjection');

    $response->assertStatus(200);
});
