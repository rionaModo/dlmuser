<?php
                                                     //是否采用压缩文档


define('STATIC_PRE', '/dlmobile');                                    //静态资源前缀,最后无‘/’;
define('EVN_NAME', 'test');                                                             //环境配置--开发环境
define('STATIC_FROM', "no");
//define('STATIC_PRE', 'http://static.danlu.com');                                    //静态资源前缀,最后无‘/’;
//define('EVN_NAME', 'pro');                                                             //环境配置--开发环境

define('REDIS_IP','192.168.100.51');                                           //配置 redis 的ip
define('REDIS_PORT','19002');                                                   //配置redis的端口




$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://192.168.100.63:9200/login",
    "auth"=>"http://192.168.100.63:9200/serviceValidate",
    "logout"=>"http://192.168.100.63:9200/logout"
);



$GLOBALS['evnCfg']=array(
    'dlsc'=>array(   //dlsc
        'baseUrl'=>'http://192.168.100.63:10214',
        'sourceCfg'=>RESOURCES_CFG.'/dlsc.php'
    ),
    'dluc'=>array(
        'baseUrl'=>'http://192.168.100.63:10217',
        'sourceCfg'=>RESOURCES_CFG.'/dluc.php'
    ),
    'dlcategory'=>array(
        'baseUrl'=>'http://192.168.100.63:10203/category',
        'sourceCfg'=>RESOURCES_CFG.'/dlcategory.php'
    )
);