<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$index_name = "article_v1";

if ($client->indices()->exists(['index' => $index_name])) {
    print_r($client->indices()->getMapping(['index' => $index_name]));
    return;
}

$resp = $client->indices()->create([
    'index' =>$index_name,
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
                    ],
                    "pinyin_analyzer" => [
                        "tokenizer" => "pinyin_tokenizer",
                    ],
                ],
                "tokenizer" => [
                    "pinyin_tokenizer" => [
                        "type" => "pinyin",
                        "keep_separate_first_letter" => false,
                        "keep_full_pinyin" => true,
                        "keep_original" => false,
                        "limit_first_letter_length" => 16,
                        "lowercase"  => true,
                        "ignore_pinyin_offset" => false,
                    ]
                ]
            ]
        ],
        'mappings' => [
            'doc' => [ // ES6.0以后的版本取消了type, 所以固定一个type名字即可
                'properties' => [
                    'article_id' => [
                        'type' => 'long',
                    ],
                    'article_channel' => [
                        'type' => 'keyword',
                    ],
                    'article_title' => [
                        'type' => 'text', // article_title中文分词
                        'fields' => [
                            'pinyin' => [
                                'type' => 'text', // article_title.pinyin采用拼音分词
                                'analyzer' => 'pinyin_analyzer',
                                'search_analyzer' => 'pinyin_analyzer',
                            ]
                        ],
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

$stats = $client->indices()->getMapping(['index' => $index_name]);
print_r($stats);