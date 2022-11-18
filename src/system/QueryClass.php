<?php

namespace Sophokles\Graphql\System;

abstract class QueryClass extends QueryObjectClass
{
    protected string $type = 'query';
    abstract protected function resolve(array $args = []);
}
