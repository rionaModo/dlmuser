<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 20:43
 */
define('STATIC_PRE', 'http://static.danlu.com/dlmobile');                                    //静态资源前缀,最后无‘/’;
define('EVN_NAME', 'prod');                                                             //环境配置--开发环境
define('STATIC_FROM', "yes");                                                             //是否采用压缩文档


define('REDIS_IP','172.19.0.38');                                           //配置 redis 的ip
define('REDIS_PORT','19001');                                                   //配置redis的端口



$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://sso.danlu.com/login",
    "auth"=>"http://sso.danlu.com/serviceValidate",
    "logout"=>"http://sso.danlu.com/logout"
);


$GLOBALS['evnCfg']=array(
    'dlsc'=>array(
        'baseUrl'=>'http://172.19.0.199:9000',
        'sourceCfg'=>RESOURCES_CFG.'/dlsc.php'
    ),
    'dluc'=>array(
        'baseUrl'=>'http://172.19.0.104:9000',
        'sourceCfg'=>RESOURCES_CFG.'/dluc.php'
    ),
    'dlcategory'=>array(
        'baseUrl'=>array(
            'http://172.19.0.11:9000',
            'http://172.19.0.80:9000'
        ),
        'sourceCfg'=>RESOURCES_CFG.'/dlcategory.php'
    )
);