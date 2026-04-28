<?php

namespace App\Repositories\Contracts;

interface AboutMeRepositoryInterface
{
    public function get(); // Since there is only one record, we'll fetch the first one
    public function update(array $data); // Updates the single record
}
