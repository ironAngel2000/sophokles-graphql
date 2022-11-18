<?php

namespace Sophokles\Graphql\System;

class Types
{
    public static function type($name)
    {
        if (class_exists($name)) {
            return new $name();
        }
    }

}
