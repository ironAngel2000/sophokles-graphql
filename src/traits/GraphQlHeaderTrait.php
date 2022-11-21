<?php

namespace Sophokles\Graphql\Traits;

use GraphQL\Server\Helper;
use Sophokles\Graphql\System\GraphQLSchema;
use Sophokles\Graphql\System\QueryObjectClass;
use System\Config\sysconfig;

trait GraphQlHeaderTrait
{

    protected array $queries = [];
    protected array $headers = [];
    protected $queryType;
    protected $queryName;

    protected function getHeader(string $key): string
    {

        $ret = '';
        $headKey = 'HTTP_' . strtoupper($key);
        if (isset($_SERVER[$headKey])) {
            $ret = trim($_SERVER[$headKey]);
        }

        $headKey = 'REDIRECT_HTTP_' . strtoupper($key);
        if (isset($_SERVER[$headKey])) {
            $ret = trim($_SERVER[$headKey]);
        }


        return $ret;
    }


    final protected function traitInit()
    {

        $config = sysconfig::graphql();

        foreach ($config['headers'] as $headerKey) {
            $this->headers[$headerKey] = $this->getHeader($headerKey);
        }

        foreach ($config['schemas'] as $schemaClass) {
            $objSchema = new $schemaClass();
            if ($objSchema instanceof GraphQLSchema) {
                $this->queries = $objSchema->getQueries();
            }
        }

        $objHelper = new Helper();

        $request = $objHelper->parseHttpRequest();

        $query = $request->query;
        $this->request = $request;

        $this->extractQuery($query);
    }

    final protected function run()
    {
        header("Content-type:application/json");

        if (!isset($this->queries[$this->queryName])) {
            throw new \Exception('query not found');
        }

        $objQuery = new $this->queries[$this->queryName]($this->headers);
        if ($objQuery instanceof QueryObjectClass) {
            $objQuery->setHeader($this->headers);
            $objQuery->execute();
        }

    }

    protected function extractQuery(string $query)
    {
        $query = strtolower(trim($query));
        $type = '';
        $resolve = '';

        if (stristr($query, 'query')) {
            $type = 'query';
        }
        if (stristr($query, 'mutation')) {
            $type = 'mutation';
        }

        if ($type !== '') {
            $resolve = explode($type, $query);
            $resolve = array_pop($resolve);
            $resolve = str_replace(["\r", "\n", " ", "  ", "\t"], '', $resolve);
            $resolve = explode('{', $resolve);
            foreach ($resolve as $name) {
                if (trim($name) !== '') {
                    $resolve = $name;
                    break;
                }
            }
            $resolve = explode('(', $resolve);
            $resolve = trim($resolve[0]);
        }

        $this->queryType = $type;
        $this->queryName = $resolve;

    }

}
