<?php

require 'vendor/autoload.php';

$client = \Elasticsearch\ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200'],
    'retries' => 2,
]);

$resp = $client->index([
    'index' => 'article',
    'type' => 'doc',
    'id' => 1,
    'body' => [
        'article_id' => 7696274,
        'article_channel' => 'youhui',
        'article_title' => '安卓数据线 安全快充 智能机通用',
        'article_url' => 'https://detail.tmall.com/item.htm?id=530160087783',
        'article_pic' => 'http://qny.smzdm.com/201801/15/5a5c53600a8e66418.jpg',
        'article_content' => '安卓数据线 安全快充 智能机通用3.9元，使用1元优惠券后2.9元到手',
        'article_is_anonymous' => false,
        'article_user_info' => [
            'user_id' => 72326591,
            'user_avator' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=1064104630,1322391597&fm=173&s=7E900DC57C1190C6051961A90300A012&w=218&h=146&img.JPEG',
            'user_nickname' => 'elastic小白'
        ],
        'article_tags' => ['超值', '优惠打折', '安卓数据线'],
        'article_category' => [
            ['cate_id' => 16, 'cate_name' => '手机', 'cate_depth' => 1],
            ['cate_id' => 17, 'cate_name' => '配件', 'cate_depth' => 2],
            ['cate_id' => 18, 'cate_name' => '安卓', 'cate_depth' => 3],
        ],
        'article_comment_count' => 876,
        'article_like_count' => 98,
        'article_pub_date' => intval(microtime(true) * 1000),
        'article_extend_data' => [
            'price' => '350元',
            'coupon' => ['满300减50', '立减20'],
        ],
    ]
]);

print_r($resp);

$doc = $client->get(['index' => 'article', 'type' => 'doc', 'id' => 1]);
print_r($doc);