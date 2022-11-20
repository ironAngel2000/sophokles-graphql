<?php

namespace Sophokles\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sophokles\Dataset\dataset;
use Sophokles\Dataset\typeBoolean;
use Sophokles\Dataset\typeFloat;
use Sophokles\Dataset\typeInt;
use Sophokles\Dataset\typeJson;
use Sophokles\Dataset\typeText;

abstract class ModelType extends ObjectType
{

    public function __construct(public $name, protected dataset $model, public $description = '')
    {
        $fields = $this->resolveModel();

        $config = [
            'name' => $this->name,
            'description' => $this->description,
            'fields' => $fields,
        ];

        parent::__construct($config);
    }

    protected function resolveModel(): array
    {
        $fields = [];

        foreach ($this->model as $item => $type) {
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
                ];
            }
        }

        return $fields;
    }

}
