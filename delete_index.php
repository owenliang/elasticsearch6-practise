<?php
require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$resp = $client->indices()->delete(['index' => 'article_v1']);
print_r($resp);