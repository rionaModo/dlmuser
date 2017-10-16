<?php

define('STATIC_PRE', 'http://static.danlu.com/dlmobile');                                    //静态资源前缀,最后无‘/’;
define('EVN_NAME', 'uat');                                                             //环境配置--开发环境
define('STATIC_FROM', "yes");                                                             //是否采用压缩文档


define('REDIS_IP','10.163.12.171');                                           //配置 redis 的ip
define('REDIS_PORT','19001');                                                   //配置redis的端口



$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://123.57.152.182:9000/login",
    "auth"=>"http://123.57.152.182:9000/serviceValidate",
    "logout"=>"http://123.57.152.182:9000/logout"
);

$GLOBALS['evnCfg']=array(
    'dlsc'=>array(
        'baseUrl'=>'http://59.110.0.184:9000',
        'sourceCfg'=>RESOURCES_CFG.'/dlsc.php'
    ),
    'dluc'=>array(
        'baseUrl'=>'http://123.57.224.109:9002',
        'sourceCfg'=>RESOURCES_CFG.'/dluc.php'
    ),

    'dlcategory'=>array(
        'baseUrl'=>'http://123.57.216.123:9000',
        'sourceCfg'=>RESOURCES_CFG.'/dlcategory.php'
    )
);