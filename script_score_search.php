<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$resp = $client->search([
    'index' => 'article',
    'body' => [
        'query' => [
            'function_score' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                // 中文分词或者拼音分词匹配皆可, 综合打分
                                'multi_match' => [
                                    'query' => '数据线',
                                    'fields' => [
                                        'article_title', 'article_title.pinyin', 'article_content'
                                    ],
                                    'type' => 'most_fields',
                                ]
                            ],
                        ],
                        // 是/否的判定, 写在filter context中，比如term过滤
                        'filter' => [
                            [
                                'term' => ['article_is_anonymous' => false]
                            ],
                            [
                                'nested' => [
                                    'path' => 'article_category',
                                    'query' => [
                                        'term' => ['article_category.cate_name' => '手机'],
                                    ],
                                ],
                            ]
                        ]
                    ],
                ],
                'functions' => [

                ]
            ]
        ]
    ]
]);

print_r($resp);