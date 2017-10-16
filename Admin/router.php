<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 11:11
 */
header("Content-Type: text/html;charset=utf-8");
ini_set('date.timezone','Asia/Shanghai');
include_once '../common/cfg/init.php';                          //初始化一些公共变量
include_once './cfg/init.php';                          //初始化一些项目私有化常量
include_once EVN_CFG;                                         //环境相关的配置文件
if(EVN_NAME=='prod'){
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
include_once R_ROOT.'/common/common/common.php';                 //公用函数
include_once R_ROOT.'/common/common/class/session.php';         //链接session
include PRIVI_ROOT.'/business/private_business.php';
include PRIVI_ROOT.'/business/api.php';
include_once R_ROOT.'/common/page_generator/index.php';          //页面入口


