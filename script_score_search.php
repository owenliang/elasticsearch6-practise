<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$resp = $client->putScript([
    'id' => 'my_scoring',
    'body' => [
        'script' => [
            'lang' => 'painless',
            'source' => <<< EOF
            
            // 热度作为一个排序因素
            def hot = doc["article_like_count"].value * doc["article_comment_count"].value;
            if (hot == 0) {
                hot = 1;
            }
            def hot_score = Math.log1p(1);
            return hot_score;
EOF
        ]
    ]
]);

print_r($resp);

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
                    [
                        'script_score' => [ // 脚本打分
                            'script' => [
                                "id" => "my_scoring",
                                'params' => [
                                    'cur_date' => intval(microtime(true) * 1000),
                                ],
                            ],
                        ]
                    ],
                    [
                        'gauss' => [ // 时间衰减, 1天内不衰减, 2天的衰减一半
                            'article_pub_date' => [
                                'origin' => intval(microtime(true) * 1000),
                                'offset' => '1d',
                                'scale' => '2d',
                                'decay' => 0.5,
                            ]
                        ]
                    ],
                ],
                'boost_mode' => 'multiply',
                'score_mode' => 'multiply',
            ]
        ]
    ]
]);

print_r($resp);