<?php

namespace Sophokles\Graphql\Traits;

use GraphQL\Type\Definition\Type;
use Sophokles\Dataset\dataset;
use Sophokles\Dataset\typeBoolean;
use Sophokles\Dataset\typeFloat;
use Sophokles\Dataset\typeInt;
use Sophokles\Dataset\typeJson;
use Sophokles\Dataset\typeText;

trait ModelTypeTrait
{

    protected function resolveModel(dataset $model): array
    {
        $fields = [];

        foreach ($model as $item => $type) {
            if ($type instanceof typeBoolean) {
                $fields[$item] = [
                    'type' => Type::boolean(),
                ];
            }
            if ($type instanceof typeInt) {
                $fields[$item] = [
                    'type' => Type::int(),
                ];
            }
            if ($type instanceof typeText) {
                $fields[$item] = [
                    'type' => Type::string(),
                ];
            }
            if ($type instanceof typeFloat) {
                $fields[$item] = [
                    'type' => Type::float(),
                ];
            }
            if ($type instanceof typeJson) {
                $fields[$item] = [
                    'type' => Type::string(),
                    'resolve' => static fn(typeJson $item): string => $item->getJsonString()
                ];
            }
        }

        return $fields;
    }
}
