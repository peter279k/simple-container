<?php

namespace Lee\Container\Tests;

use stdClass;
use Exception;
use Lee\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testShouldInjectConstructorWithoutDefaultValue()
    {
        $container = new Container();
        $container->set(ProfileWithoutDefaultValue::class);
        $profile = $container->get(ProfileWithoutDefaultValue::class, ['userName' => 'Peter']);

        $this->assertSame('Peter', $profile->getUserName());
    }

    public function testShouldInjectConstructorWithDefaultValue()
    {
        $container = new Container();
        $container->set(ProfileWithDefaultValue::class);
        $profile = $container->get(ProfileWithDefaultValue::class);

        $this->assertSame('lee', $profile->getUserName());
    }

    public function testShouldThrowExceptionWithIncorrectParameterName()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can not resolve class dependency userName');

        $container = new Container();
        $container->set(ProfileWithOutDefaultValue::class);
        $container->get(ProfileWithOutDefaultValue::class, ['incorrectName' => 'Peter']);
    }

    public function testShouldInjectConstructorWithEmptyParameters()
    {
        $container = new Container();
        $container->set(stdClass::class);
        $stdClass = $container->get(stdClass::class, []);

        $this->assertInstanceOf(stdClass::class, $stdClass);
    }

    public function testGetShouldInjectUnregisteredClass()
    {
        $container = new Container();
        $stdClass = $container->get(stdClass::class, []);

        $this->assertInstanceOf(stdClass::class, $stdClass);
    }

    public function testSetShouldThrowExceptionWithAbstractClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Class PHPUnit\Framework\TestCase is not instantiable');

        $container = new Container();
        $container->get(TestCase::class, []);
    }
}
