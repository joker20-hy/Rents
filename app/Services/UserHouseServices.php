<?php

namespace App\Services;

use App\Models\UserHouse;

class UserHouseServices
{
    protected $userHouse;

    public function __construct(UserHouse $userHouse)
    {
        $this->userHouse = $userHouse;
    }

    /**
     * Create user_house record
     *
     * @param array $params ['user_id', 'house_id', 'role']
     *
     * @return \App\Models\UserHouse
     */
    public function store(array $params)
    {
        $userHouse = $this->userHouse->create($params);
        return $userHouse;
    }
}
