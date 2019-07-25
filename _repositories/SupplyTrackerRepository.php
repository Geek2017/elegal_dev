<?php

namespace Repositories;

use App\SupplyTracker;

class SupplyTrackerRepository
{
    public function getAll()
    {
        return SupplyTracker::get();
    }

    public function add($inputs)
    {
        return SupplyTracker::create($inputs);
    }
}
