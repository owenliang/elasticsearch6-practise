<?php
require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$resp = $client->indices()->analyze([
    'index' => 'article',
    'body' => [
        'analyzer' => 'pinyin_analyzer',
        'text' => '刘德华',
    ]
]);

print_r($resp);