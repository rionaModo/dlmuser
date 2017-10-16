<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 15:55
 */
//模板的渲染
include R_ROOT . '/common/page_generator/Base.php';


Base::init();



//$GLOBALS['smarty']->caching = true;
//$GLOBALS['smarty']->cache_lifetime = 120;



$GLOBALS['smarty']->display($GLOBALS['pageName'].SUFFIX);