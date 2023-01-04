<?php

namespace Sophokles\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sophokles\Database\FieldType;
use Sophokles\Dataset\dataset;

abstract class ModelType extends ObjectType
{

    public function __construct(string $typeName, protected dataset $model, public $description = '')
    {
        $fields = $this->resolveModel();

        $config = [
            'name' => $typeName,
            'description' => $this->description,
            'fields' => $fields,
        ];

        parent::__construct($config);
    }

    protected function resolveModel(): array
    {
        $fields = [];

        $dataModel = $this->model->getDataModel();

        foreach ($dataModel as $item => $type) {
            switch ($type) {
                case FieldType::BIT:
                case FieldType::INT:
                case FieldType::BIGINT:
                case FieldType::TIMESTAMP:
                    $fields[$item] = [
                        'name' => $item,
                        'type' => Type::int(),
                    ];
                    break;
                case FieldType::BOOLEAN;
                    $fields[$item] = [
                        'name' => $item,
                        'type' => Type::boolean(),
                    ];
                    break;
                case FieldType::DECIMAL;
                    $fields[$item] = [
                        'name' => $item,
                        'type' => Type::float(),
                    ];
                    break;
                default:
                    $fields[$item] = [
                        'name' => $item,
                        'type' => Type::string(),
                    ];
                    break;
            }

            $fields[$item]['resolve'] = function($rootValue, $args) use ($item) {
                $ret = null;

                if(isset($rootValue[$item])){
                    $ret = $rootValue[$item];
                }

                return $ret;
            };

        }

        return $fields;
    }
}
