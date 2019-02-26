# simple-container

## Introduction

This is abou the simple container to help developers to understand how the Reflection works.

## Usage

Firstly, you have to specify a clas that you want to inject.

For example, we assume that you want to inject following `Profile` class:

```php

class Profile
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

```

Then we use the `Container` class to inject this `Profile` class.

```php
use Lee\Container\Container;

$container = new Container();
$container->set(Profile::class);
$profile = $container->get(Profile::class);

echo $profile->getUserName(); // lee
```

If you want to inject class that its constructor arguments is without the default value, we should specify them by ourselves.

The sample codes are as follows:

```php
class Profile
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
```

Then we use `Container` class to inject this class.

```php
use Lee\Container\Container;

$container = new Container();
$container->set(Profile::class);
$profile = $container->get(Profile::class, ['userName' => 'Peter']);

echo $profile->getUserName(); // Peter
```
