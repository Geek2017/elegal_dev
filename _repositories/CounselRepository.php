<?php

namespace Repositories;

use App\Counsel;

class CounselRepository
{
    public function getCounsels()
    {
        $counsels = Counsel::select([
                'counsels.id'
            ])
            ->with([
                'profile'
            ])
            ->join('profiles', 'profiles.counsel_id', '=', 'counsels.id')
            ->orderBy('profiles.lastname')
            ->get();

        return $counsels;
    }
    public function getCounselById($id)
    {
        $counsel = Counsel::select([
                'counsels.id'
            ])
            ->with([
                'profile'
            ])
            ->where('id', $id)
            ->first();

        return $counsel;
    }
}
