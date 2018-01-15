# elasticsearch6-practise

## 背景

公司打算落地ES6.0+，所以做一些必要的DEMO作为调研。

## 依赖

使用了2个分词插件：

* IK中文分词：https://github.com/medcl/elasticsearch-analysis-ik
* 拼音分词：https://github.com/medcl/elasticsearch-analysis-pinyin

基于上述2个插件，可以提高文档召回率。

## 文件

* create_index：
    * 创建索引，默认全文字段采用IK分词。
    * 对于有拼音搜索的字段，额外增加拼音分词。
    * enable=false字段用于存储任意类型的扩展信息。
* delete_index：删除索引
* index_alias：索引别名，线上环境需要热切换index。
* document_index：创建文档。
* query_score_search：默认查询相关性排序的搜索。
* script_score_search：脚本计算相关性排序的搜索。