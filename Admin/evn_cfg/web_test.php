<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 20:43
 * 阿里test
 */
define('STATIC_PRE', 'http://static.danlu.com/dlmobile');                                    //静态资源前缀,最后无‘/’;
define('EVN_NAME', 'test');                                                             //环境配置--开发环境
define('STATIC_FROM', "yes");                                                             //是否采用压缩文档
//define('STATIC_PRE', 'http://static.danlu.com');                                    //静态资源前缀,最后无‘/’;
//define('EVN_NAME', 'pro');                                                             //环境配置--开发环境
define('REDIS_IP','192.168.100.21');                                           //配置 redis 的ip
define('REDIS_PORT','6380');                                                   //配置redis的端口



$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://sso.web.danlu.com/login",
    "auth"=>"http://sso.web.danlu.com/serviceValidate",
    "logout"=>"http://sso.web.danlu.com/logout"
);
$GLOBALS['evnCfg']=array(
    'dlsc'=>array(
        'baseUrl'=>'http://sc.web.danlu.com',
        'sourceCfg'=>RESOURCES_CFG.'/dlsc.php'
    ),
    'dluc'=>array(
        'baseUrl'=>'uc.web.danlu.com',
        'sourceCfg'=>RESOURCES_CFG.'/dluc.php'
    ),
    'dlic'=>array(
        'baseUrl'=>'http://ic.web.danlu.com',
        'sourceCfg'=>RESOURCES_CFG.'/dlic.php'
    )
);