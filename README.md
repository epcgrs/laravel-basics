# Emmanuelpcg laravel-basics

![Stars](https://img.shields.io/github/stars/epcgrs/laravel-basics)
[![GitHub license](https://img.shields.io/github/license/epcgrs/laravel-basics)](https://github.com/epcgrs/laravel-basics/blob/main/LICENSE)
![Packagist Downloads](https://img.shields.io/packagist/dt/emmanuelpcg/laravel-basics)
![Packagist Version](https://img.shields.io/packagist/v/emmanuelpcg/laravel-basics)

## Description
___
Package with basic starter features for Laravel.

- [Install](#install)
- [If Builder](#if-builder)
- [Constants](#constants)
- [Query Builder Apply Filters](#query-builder-filters)
- [Model Basics](#model-basics)
- [Image Manipulation](#image-manipulation)

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

<a id="query-builder-filters"></a>
## Query Builder Apply Filters

Add Query filters in Pipelines Requests

For example: I have Car model and want to apply a filter by color attribute 

create a class with name of attribute:

```php
<?php

namespace App\QueryFilters\Cars;

use Emmanuelpcg\Basics\QueryFilters\Operators\Equals;

class Color extends Equals { }

```
And for example in Service Repository Pattern:

In your Repository you can do this:

```php
<?php

namespace App\Repositories;

use App\Models\Car;
use App\QueryFilters\Cars\Color;
use Emmanuelpcg\Basics\Repositories\ModelBasic;
use Illuminate\Database\Eloquent\Model;

class CarsRepository extends ModelBasic
{
    protected function getEntityInstance(): Model
    {
        return new Car();
    }

    public function paginated(int $perPage) 
    {
        return parent::__pipeApplyFilter(
            [Color::class],
            $this->getEntityInstance()->query()
        )->paginate($perPage);
    }
}
```

And now if you pass color in request by query param GET the filter will be applied.

<a id="model-basics"></a>
## Model Basics Examples

```php 
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Emmanuelpcg\Basics\Repositories\ModelBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository extends ModelBasic implements IUserRepository
{
    protected function getEntityInstance(): Model
    {
        return new User();
    }

    public function getByEmail(string $email): ?User
    {
        return parent::__byColumn('email', $email);
    }

    public function getByKey(int $id): ?User
    {
        return parent::__byKey($id);
    }

    public function create(array $data): ?User
    {
        return parent::__create($data);
    }

    public function update(int $id, array $data): ?User
    {
        return parent::__updateByKey($id, $data);
    }

    // if isset $data['id'] is update if not, is create
    public function save(array $data): ?User
    {
        return parent::__save($data);
    }

    public function get(): ?Collection
    {
        return parent::__all();
    }

    public function getWhereActive(): ?Collection
    {
        return parent::__allWhere('active', 1);
    }

    public function delete(int $id): bool
    {
        return parent::__delete($id);
    }

    public function getPrimaryKeyName(): string
    {
        return parent::__getEntityKeyName();
    }
}

```
<a id="image-manipulation"></a>
## Image Manipulation

```php
<?php

namespace App\Http\Controllers;

use Emmanuelpcg\Basics\ImageManipulation\ImageManipulation;
use Illuminate\Http\Request;
use Exception;

class UploadsController extends Controller
{
    use ImageManipulation;

    public function upload(Request $request)
    {
        try {
            return $this->resizeAndSaveImage(
                'avatar', // name of field
                'avatars', // name of disk 
                300, // width
                300, // height
                'user-upload', // new name of image, will be concatenated with timestamp
                'png' // format to save 
            );
        } catch (Exception $exception) {
            return abort(400, $exception->getMessage());
        }
    }
}

```
