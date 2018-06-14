<?php
/**
 * @Author:      fyd
 * @DateTime:    2018/6/8 16:22
 * @Description: 配置信息
 */
return [
    //视图输出字符串内容替换
    'view_replace_str'      =>  [
        '__PUBLIC__'        =>  SITE_URL.'/public/static/index',
        '__MODULE__'        =>  SITE_URL.'/public',

        // 生成Index模块的样式模板
        '__INDEX__'         =>  SITE_URL.'/public/index',
        '__INDEX_CSS__'     =>  SITE_URL.'/public/static/index/css',
        '__INDEX_JS__'      =>  SITE_URL.'/public/static/index/js',
        '__INDEX_IMG__'     =>  SITE_URL.'/public/static/index/img',
        '__INDEX_VENDOR__'  =>  SITE_URL.'/public/static/index/vendors',
    ],

    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
        'page_size' => 5, //页码数量
        'page_button'=>[
            'total_rows'=>true, //是否显示总条数
            'turn_page'=>true, //上下页按钮
            'turn_group'=>true, //上下组按钮
            'first_page'=>true, //首页
            'last_page'=>true  //尾页
        ]
    ]
];