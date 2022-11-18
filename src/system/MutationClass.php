<?php

namespace Sophokles\Graphql\System;

abstract class MutationClass extends QueryObjectClass
{
    protected string $type = 'mutation';
    abstract protected function resolve(array $args = []);
}
