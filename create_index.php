<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200', 'localhost:9201', 'localhost:9203'],
    'retries' => 2,
]);

if ($client->indices()->exists(['index' => 'article'])) {
    die("index exsited");
}

$resp = $client->indices()->create([
    'index' => 'article',
    'body' => [
        'settings' => [
            "number_of_shards" => 3,
            "number_of_replicas" => 2,
            'analysis' => [
                'analyzer' => [
                    'default' => [
                        'type' => 'ik_max_word',
                    ],
                    'default_search' => [
                        'type' => 'ik_max_word',
                    ]
                ]
            ]
        ],
        'mappings' => [
            'doc' => [
                'properties' => [
                    'article_id' => [
                        'type' => 'long',
                    ],
                    'article_title' => [
                        'type' => 'text',
                    ],
                    'article_url' => [
                        'type' => 'keyword',
                    ],
                    'article_pic' => [
                        'type' => 'keyword',
                    ],
                    'article_content' => [
                        'type' => 'text',
                    ],
                    'article_is_anonymous' => [
                        'type' => 'boolean',
                    ],
                    'article_user_info' => [
                        'properties' => [
                            'user_id' => [
                                'type' => 'long',
                            ],
                            'user_avator' => [
                                'type' => 'keyword',
                                'index' => false,
                            ],
                            'user_nickname' => [
                                'type' => 'keyword',
                            ],
                        ],
                    ],
                    'article_tags' => [
                        'type' => 'keyword',
                    ],
                    'article_category' => [
                        'type' => 'nested',
                        'properties' => [
                            'cate_depth' => [
                                'type' => 'integer',
                            ],
                            'cate_name' => [
                                'type' => 'keyword',
                            ],
                            'cate_id' => [
                                'type' => 'long',
                            ]
                        ]
                    ],
                    'article_comment_count' => [
                        'type' => 'integer',
                    ],
                    'article_like_count' => [
                        'type' => 'integer',
                    ],
                    'article_pub_date' => [
                        'type' => 'date',
                    ],
                    'article_extend_data' => [
                        'enabled' => false,
                    ]
                ]
            ],
        ],
    ],
]);

print_r($resp);

$stats = $client->indices()->getMapping(['index' => 'article']);
print_r($stats);