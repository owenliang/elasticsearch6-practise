<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200', 'localhost:9201', 'localhost:9203'],
    'retries' => 2,
]);

$alias_name = "article";
$index_name = "article_v1";

$client->indices()->updateAliases([
    'body' => [
        'actions' => [
            ['remove' => ['index' => '*', 'alias' => $alias_name]],
            ['add' => ['index' => $index_name, 'alias' => $alias_name]],
        ],
    ],
]);

$resp = $client->indices()->getAlias(['name' => $alias_name]);
print_r($resp);

