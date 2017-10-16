<?php
/**
 *   $querystring:Get的请求字符串
 *   $rawdata：POST请求体，$rawdataObj：将$rawdata转换为对象
 */
return array(
    'tpl' => array(
        'example' => array(
            'dataKey'=>array(
                'method'=>'GET',
                'qs'=>"userName=kk&&id="
            )
        )
    ),
    'router' => array(  //路由的key用rooter后的路径   如//router/coupon/detail  key=coupon/detail
        'example/test'=>array(
            'dataKey2'=>array(
             //   'baseUrl'=> 'http://192.168.40.57:7080',
                'method'=>'GET',
                'qs'=>array(
                    'isValidate'=>empty($_GET['company'])?'':$_GET['company']
                )
            )
        )
    )
);