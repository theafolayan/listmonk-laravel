# Listmonk Laravel

A Laravel package for interacting with the Listmonk API.

## Installation

1. Install the package via Composer:

    ```sh
    composer require theafolayan/listmonk-laravel
    ```

2. Publish the configuration file:

    ```sh
    php artisan vendor:publish --tag=listmonk-config
    ```

3. Configure your `.env` file with the following variables:

    ```env
    LISTMONK_BASE_URL=http://localhost:9000
    LISTMONK_USERNAME=admin
    LISTMONK_PASSWORD=admin
    ```

## Usage

### Example: Manage Subscribers

Get all subscribers:

```php

use Theafolayan\ListmonkLaravel\Facades\Listmonk;

$subscribers = Listmonk::getSubscribers();

```

### Add a subscriber

```php
$newSubscriber = Listmonk::createSubscriber([
    'email' => 'john.doe@example.com',
    'name' => 'John Doe',
    'status' => 'enabled',
]);
```

### Example: Manage Lists

Get all lists:

```php
$lists = Listmonk::getLists();
```

### Create a new list

```php
$newList = Listmonk::createList([
    'name' => 'Weekly Newsletter',
    'description' => 'Updates and news every week.',
]);
```

## Facade

You can use the `Listmonk` facade to interact with the Listmonk API:

```php
use Theafolayan\ListmonkLaravel\Facades\Listmonk;

$subscribers = Listmonk::getSubscribers();
$list = Listmonk::createList(['name' => 'New List']);
```

## Dependency Injection

Alternatively, you can inject the Listmonk class into your services:

```php

use Theafolayan\ListmonkLaravel\Listmonk;

class YourService
{
    protected $listmonk;

    public function __construct(Listmonk $listmonk)
    {
        $this->listmonk = $listmonk;
    }

    public function getSubscribers()
    {
        return $this->listmonk->getSubscribers();
    }
}
```

## Methods

- `getSubscribers(array $filters = [])`
- `createSubscriber(array $data)`
- `getLists()`
- `createList(array $data)`

## Contributing

Contributions are welcome! To contribute:

- Fork the repository.
- Create a feature branch.
- Submit a pull request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/license/mit).
