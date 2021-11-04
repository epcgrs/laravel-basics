# Emmanuelpcg laravel-basics

![Stars](https://img.shields.io/github/stars/epcgrs/laravel-basics)
[![GitHub license](https://img.shields.io/github/license/epcgrs/laravel-basics)](https://github.com/epcgrs/laravel-basics/blob/main/LICENSE)
[![Latest Stable Version](http://poser.pugx.org/emmanuelpcg/laravel-basics/v)](https://packagist.org/packages/emmanuelpcg/laravel-basics)
[![Total Downloads](http://poser.pugx.org/emmanuelpcg/laravel-basics/downloads)](https://packagist.org/packages/emmanuelpcg/laravel-basics)

## Description
___
Package with basic starter features for Laravel.

- [Install](#install)
- [If Builder](#if-builder)
- [Constants](#constants)

<a id="install"></a>
## Install

```shell
composer require emmanuelpcg/laravel-basics
```

<a id="if-builder"></a>
## If Builder

Creates an eloquent builder that checks a condition to be executed

In ``app/providers/AppServiceProvider.php`` add:

```php 
public function register()
{
    BuilderQueries::builderIf();
}
```

without operator param:

```php
$cars = Cars::where('color', 'red')
        ->if(auth()->check(), 'color', 'blue')
        ->get();
```

with operator param:

```php
$cars = Cars::where('color', 'red')
        ->if(auth()->check(), 'color', '!=', 'red')
        ->get();
```

<a id="constants"></a>
## Constants

creates an object with extra features of the constants:

```php 
class CarTypes extends Constants
{
    const MUSCLE = 1;
    const SPORT = 2;
}


CarTypes::getConstants();

/**
[
    "MUSCLE" => 1,
    "SPORT" => 2
 ]
*/

CarTypes::getValues();

/** [1, 2] */

CarTypes::toSelectOptions();

/**
[
    [
        'value' => 1,
        'text' => "MUSCLE"
    ],
    [
        'value' => 2,
        'text' => "SPORT"
    ]
]
*/
```

