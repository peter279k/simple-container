<?php

namespace Lee\Container\Tests;

class ProfileWithoutDefaultValue
{
    protected $userName;

    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }
}
