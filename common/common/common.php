<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/12/2
 * Time: 17:06
 */

/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @return void
 */


function ajaxReturn($data, $type = 'JSON')
{
    switch (strtoupper($type)) {
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE));
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
    }
}

/*
 *isValidate:针对某个key绝对不能为空
 * @param $key[,$tips='传入参数缺失！'][,$noreturn=true]
 * @return bool
 * */

function isValidate($value,$tips='传入参数缺失！',$noreturn=true){
   $value=empty($value);
   if($noreturn===true&&$value){
       die($tips);
    }
    if(!$value){
        return true;
    }
    return false;
}

/*
 * 解析xml
 * @param xmstring
 * @return array
 * */
function xml2assoc($xml) {
    $tree = null;
    while($xml->read())
        switch ($xml->nodeType) {
            case XMLReader::END_ELEMENT: return $tree;
            case XMLReader::ELEMENT:
                $node = array($xml->name => $xml->isEmptyElement ? '' : xml2assoc($xml));
                if($xml->hasAttributes)
                    while($xml->moveToNextAttribute())
                        $node['attributes'][$xml->name] = $xml->value;
                $tree[] = $node;
                break;
            case XMLReader::TEXT:
            case XMLReader::CDATA:
                $tree .= $xml->value;
        }
    return $tree;
}

/**
 * curl POST请求
 * @param $durl
 * @param array $data
 * @return mixed
 */
 function curl_post_contents($durl, $data = array(),$head=array())
{
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求接口：' . $durl . '<br>';
    }
    $headers = array(
        "X-ApiVersion: 1.0"
    );
    $headers=$headers+$head;
    $ch = curl_init();
    // 设置URL和相应的选项
    $data_encode = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
    // $data_str = rawurlencode($data_encode);
    $data_str=$data_encode;
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求参数：' . $data_encode . '<br>';
    }
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, TIMEOUT);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);  //设置头信息的地方



    $r = curl_exec($ch);
    $info = curl_getinfo($ch); // 获得请求信息
    $error_info = curl_error($ch); // 获得错误信息
    curl_close($ch);
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求返回：' . $r . '<br>';
    }
    return $r;
}

/**
 * curl get方法
 * @param $durl
 * @param array $data
 * @return mixed
 */
function curl_get_contents($durl, $data = array(),$head=array())
{
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求接口：' . $durl . '<br>';
    }
    $data_encode='';
    foreach($data as $key=>$value){
        $data_encode=$data_encode.$key.'='.$value.'&';
    }
    $data_str = $data_encode;
    if (strpos($durl, '?') === false) {
        $durl .= '?' . $data_str;
    } else {
        $durl .= '&' . $data_str;
    }
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求参数：' . $data_encode . '<br>';
    }
    $headers = array(
        "X-ApiVersion: 1.0"
    );
    $headers=$headers+$head;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, TIMEOUT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  //设置头信息的地方
    $output = curl_exec($ch);
    $info = curl_getinfo($ch); // 获得请求信息
    $error_info = curl_error($ch); // 获得错误信息
    curl_close($ch);
    if (isset($_GET['debug']) && $_GET['debug'] == DEBUG_KEY) {
        echo '请求返回：' . $output . '<br>';
    }
    //@file_put_contents(LOG_PATH . 'rsa_log.txt', $data_encode . '*****' . json_encode($header) . PHP_EOL, FILE_APPEND);
    return $output;
}
/*
 * 业务相关，根据companyI获得用户信息
 *
 * */


function getUserInfo($companyId,$head=array()){
    if(empty($companyId)){
        die( "传入参数companyId为空，无法登陆！");
        return;
    }
    $requestUrl=$GLOBALS['evnCfg']['dluc']['baseUrl'].'/uc/V1/companies/ids';
    $param=array('paramsList'=> $companyId);
    $companyInfo= curl_get_contents($requestUrl,$param,$head);
    if(json_decode($companyInfo,true)){
        $companyInfo=json_decode($companyInfo,true);
    }
    if($companyInfo['status']==0&&!empty($companyInfo['data'])&&!empty($companyInfo['data'][$companyId])){
        return $companyInfo['data'][$companyId];
    }else{
        return false;
    }
}


function getSsoreUrl($query=array()){
    $nowURL='http://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path=$req_url=$_SERVER['REQUEST_URI'];
    $getString='';
    $get=$_GET+$query;
    foreach($get as $key=>$value){
        if(trim($key)!='ticket'&&trim($key)!='pagename'&&trim($key)!='api'){
            $getString=$getString.$key.'='.$value.'&';
        }
    }
    if(strpos($req_url,'?')>=0){
        $path=explode('?',$req_url)[0];
    }
    $reLocURL='http://'. $_SERVER['HTTP_HOST'].$path.'?'.$getString;
    return $reLocURL;
}