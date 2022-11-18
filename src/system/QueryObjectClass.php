<?php

namespace Sophokles\Graphql\System;

use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

abstract class QueryObjectClass
{
    protected string $name = '';
    protected string $responseType;
    protected array $args = [];
    protected string $type;

    abstract protected function resolve(array $args = []);

    public function __construct()
    {

    }

    public function execute()
    {
        if($this->authorization() !== true) {
            user_error('Authorization failed', E_USER_ERROR);
            die();
        }



        $config = [
            'name' => $this->name,
            'fields' => [
                $this->name => [
                    'type' => Types::type($this->responseType),
                    'args' => $this->args,
                    'resolve' => function($rootValue, array $args): array
                    {
                        return $this->resolve($args);
                    }
                ],
            ],
        ];

        $object = new ObjectType($config);

        $objConfig = SchemaConfig::create();
        switch ($this->type){
            case 'mutation':
                $objConfig->setMutation($object);
                break;
            case 'query':
                $objConfig->setQuery($object);
                break;
        }

        $objSchema = new Schema($objConfig);

        $objServer = new StandardServer(['schema' => $objSchema, 'rootValue' => []]);
        $objServer->handleRequest();

    }

    protected function authorization(): bool
    {
        return true;
    }

    public function name():string
    {
        return $this->name;
    }

    public function responseType():string
    {
        return $this->responseType;
    }

    public function arguments(): array
    {
        return $this->args;
    }

}
