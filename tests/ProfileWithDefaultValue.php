<?php

namespace Lee\Container\Tests;

class ProfileWithDefaultValue
{
    protected $userName;

    public function __construct($userName = 'lee')
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }
}
