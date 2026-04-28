<?php

it('has admin/sociallink page', function () {
    $response = $this->get('/admin/sociallink');

    $response->assertStatus(200);
});
