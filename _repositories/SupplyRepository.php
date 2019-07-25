<?php

namespace Repositories;

use App\Supply;

class SupplyRepository
{
    public function getAll()
    {
        return Supply::get();
    }

    public function add($inputs)
    {
        return Supply::create($inputs);
    }
}
