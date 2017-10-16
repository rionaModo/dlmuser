<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/12/5
 * Time: 14:33
 */
function smarty_function_file_createtime($arg, $template)
{
    if (!isset($arg['path'])) return;
    $path = parse_url($arg['path'], PHP_URL_PATH);
    $file_path = R_ROOT . $path;
    $ctime = '';
    if (file_exists($file_path)) {
        $ctime = filectime($file_path);
    }
    return empty($ctime) ? false : $ctime;
}