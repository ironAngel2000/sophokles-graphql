# sophokles-graphql extension for sophokles/framework

## Getting Started

### About

Based on <a href="https://packagist.org/packages/webonyx/graphql-php" target="_blank">webonyx/graphql-php</a>

### Installation

Use Sophokles graphql extension by Composer

```bash
composer sophokles/graphql
```
or
```json
{
    "require": {
        "sophokles/graphql": "1.*",
    }
}
```
in your composer.json

## Setup

1. creat a new local controller eg. `\local\controller\GraphQlcontroller` and implement the trait `GraphQlHeaderTrait`

```php
class GraphQlController extends abstractControllerBase
{
    use GraphQlHeaderTrait;

    protected function init()
    {
        // TODO: Implement init() method.
    }

}
```

2. route a path to the graphql controller in the systemController trait

```php
trait systemController
{
    protected function traitInit()
    {
        ...
        controller::registerRoutePath('graphql',GraphQlController::class);
        ...
    }

}
```

3. define a schema for your graphql api

```php
class MySchema extends GraphQLSchema
{

    public function toConfig(): array
    {
        return [
            GraphQLSchema::TYPE_QUERIES => [
                MyQueryClass::class
            ],
            GraphQLSchema::TYPE_MUTATIONS => [
                MyMutation::class,
            ],
        ];
    }
}
```

4. connect the schema with the graphql controller by adding a new configuration `System\Config\sysconfig.php`

```php
final class sysconfig
{
    public static function graphql()
    {
        return [
           'schemas' => [
               MySchema::class
             ],
            'headers' => [
               // here you can define header keys for the api eg 'Authorisation' or 'Language'
            ]
        ];
    }
}
```

## Types

