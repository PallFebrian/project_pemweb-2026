<?php

test('aplikasi dapat dijalankan', function () {
    $response = $this->get('/up');

    $response->assertOk();
});