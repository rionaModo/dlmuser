<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 20:43
 */
//define('EVN_NAME', 'dev');                                                             //环境配置--开发环境
//define('STATIC_PRE', '/dlmobile');                                    //静态资源前缀,最后无‘/’;
//define('STATIC_FROM', "no");                                                             //是否采用压缩文档


define('STATIC_PRE', '/museradmin');                                    //静态资源前缀,最后无‘/’;
define('EVN_NAME', 'test');                                                             //环境配置--开发环境
define('STATIC_FROM', "no");
//define('STATIC_PRE', 'http://static.danlu.com');                                    //静态资源前缀,最后无‘/’;
//define('EVN_NAME', 'pro');                                                             //环境配置--开发环境

define('REDIS_IP','192.168.100.51');                                           //配置 redis 的ip
define('REDIS_PORT','19002');                                                   //配置redis的端口



$GLOBALS['ssoCfg']=array(                                                               //sso 配置
    "login"=>"http://123.57.152.182:9000/login",
    "auth"=>"http://123.57.152.182:9000/serviceValidate",
    "logout"=>"http://123.57.152.182:9000/logout"
);



//$GLOBALS['evnCfg']=array(                                   //微服务配置
//    'promotionx'=>array(
//     //   'baseUrl'=>'http://123.57.239.53:9001',
//        'baseUrl'=>'http://127.0.0.1:7080',
//        'sourceCfg'=>RESOURCES_CFG.'/promotionx.php'
//    ),
//    'company'=>array(
//       // 'baseUrl'=>'http://123.56.71.180:9000',
//        'baseUrl'=>'http://127.0.0.1:7080',
//        'sourceCfg'=>RESOURCES_CFG.'/company.php'
//    ),
//    'category'=>array(
//        'baseUrl'=>'http://123.57.216.123:9000/category',
//        'sourceCfg'=>RESOURCES_CFG.'/category.php'
//    )
//);

$GLOBALS['evnCfg']=array(
//    'promotionx'=>array(
//        'baseUrl'=>'http://101.200.221.198:9000',
//        'sourceCfg'=>RESOURCES_CFG.'/promotionx.php'
//    ),
    'dlsc'=>array(
        'baseUrl'=>'http://192.168.100.63:10214',
        'sourceCfg'=>RESOURCES_CFG.'/dlsc.php'
    ),
    'dluc'=>array(
        'baseUrl'=>'http://192.168.100.63:10217',
        'sourceCfg'=>RESOURCES_CFG.'/dluc.php'
    ),

    'category'=>array(
        'baseUrl'=>'http://10.51.68.210:9000/category',
        'sourceCfg'=>RESOURCES_CFG.'/category.php'
    )
);
