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

define('REDIS_IP','10.171.134.153');                                           //配置 redis 的ip
define('REDIS_PORT','19001');                                                   //配置redis的端口



$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://sso.danlu.com/login",
    "auth"=>"http://sso.danlu.com/serviceValidate",
    "logout"=>"http://sso.danlu.com/logout"
);


$GLOBALS['evnCfg']=array(
    'promotionx'=>array(
        'baseUrl'=>'http://sale.danlu.com',
        'sourceCfg'=>RESOURCES_CFG.'/promotionx.php'
    ),
    'company'=>array(
        'baseUrl'=>array(
            'http://10.172.95.98:9002',
            'http://10.172.165.196:9002'
        ),
        'sourceCfg'=>RESOURCES_CFG.'/company.php'
    ),

    'category'=>array(
        'baseUrl'=>array(
            'http://10.172.95.98:9002',
            'http://10.172.165.196:9002'
        ),
        'sourceCfg'=>RESOURCES_CFG.'/category.php'
    )
);