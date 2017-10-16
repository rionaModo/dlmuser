<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/10/17
 * Time: 18:45
 */

include R_ROOT . '/common/page_generator/smarty/libs/Smarty.class.php';

class Base
{
    public static $timelimit = 8000;
    private static $redis='';

    public static function init()
    {
      //  $GLOBALS['evnCfg'] =$GLOBALS['serverCfg'];
        $GLOBALS['isRoot'] = !empty($_GET['rootname']) ? true : false;
        $GLOBALS['pageName'] = !empty($_GET['rootname']) ? $_GET['rootname'] : (!empty($_GET['pagename']) ? $_GET['pagename'] : 'index');
        $redis=$GLOBALS['redis']=self::$redis=new sSession(array("IP"=>REDIS_IP,"PORT"=>REDIS_PORT));
        $apiClass=new apiInterface();
      //  die('55666');
        self::pre_info();
        $sourceCfg = self::getAllDate();
        $reponse = self::rolling_curl($sourceCfg);
        $array = array();
        $a = array_merge($array, $reponse);
        self::root($a);
        self::smarty($a);
    }
/**
 * 登录判断
 * */
    public static function pre_info(){
        $redis=$GLOBALS['redis'];
        if($GLOBALS['isRoot']){
            return;
        }
        $no_login_page=include_once RESOURCES_CFG.'/no_login_page.php';
        if(in_array($GLOBALS['pageName'],$no_login_page)){
            return;
        }
       // $redis=$GLOBALS['redis']=self::$redis=new sSession(array("IP"=>REDIS_IP,"PORT"=>REDIS_PORT));
        $userInfo=$redis->get('userInfo');
        if($userInfo){
            return $userInfo;
        }else{
            if (empty($_GET['ticket'])) {    //未登录且链接没有ticket
                self::goLogin();
            }
            if (!empty($_GET['ticket'])) {//没有用户信心但是具有ticket
                $companyInfo= self::userInfoHandle($_GET['ticket']);
               // $userInfo=self::$redis->set('userInfo',$companyInfo);  //将用户信息存入redis
                return $userInfo;
            }
        }
        self::goLogin();
    }

/*
 * 对ticket获取用户的基础信息
 * */

    public static function userInfoHandle($ticket='')
    {
        $nowURL='http://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $result= self::curl_get_contents($GLOBALS['ssoCfg']['auth'],array('ticket'=>$ticket,'service'=>$nowURL));
        $xml = new XMLReader();
        $xml->XML($result);
        $assoc =xml2assoc($xml);
        $xml->close();
        if(empty($assoc[0]['cas:serviceResponse'][0]['cas:authenticationFailure'])){
           $userInfo=self::uerInfo($assoc);
           $companyInfo=self::companyInfo($userInfo);
            $userInfo=self::$redis->set('userInfo',$companyInfo);  //将用户信息存入redis
            return $companyInfo;
        }else{
            self::goLogin();
        }
    }
    /*
     *对用户的信息进行格式化
     * */
    static function uerInfo($array=array()){
        $user=$array[0]['cas:serviceResponse'][0]['cas:authenticationSuccess'];
        $userInfo=array();
        foreach($user as $val){
           if(empty($val['cas:user'])){
               foreach($val['cas:attributes'] as $val1){
                   foreach($val1 as $key=>$value){
                           $userInfo[trim(explode(':',trim($key))[1])]=$value;
                   }

               }
           }else{
               $userInfo['userAccount']=$val['cas:user'];
           }
        }
        return $userInfo;
    }
    /*
     * 根据companyId获取用户的店铺信息
     * */
    static function companyInfo($userInfo=array()){
        if(empty($userInfo['companyId'])){
            self::goLogin();
        }
        if($userInfo['loginType']=='terminal'){  //不知道这个参数做什么的
            return false;
        }
        $requestUrl=$GLOBALS['evnCfg']['dluc']['baseUrl'].'/uc/V1/companies/ids';
     //   $headers = array(
       //     "Content-type: application/json;charset=UTF-8"
      //  );
    //    $param=array('paramsList'=> $userInfo['companyId']);
        $companyInfo=getUserInfo($userInfo['companyId']);
        if($companyInfo){
            return $companyInfo;
        }else{
            exit('登录异常：获取用户信息失败!');
            return false;
        }
    }
    /*
     * 跳转至sso
     * */
    static function goLogin(){
        $reLocURL=getSsoreUrl();
        $loginUrl=$GLOBALS['ssoCfg']['login'].'?service='.$reLocURL;
        header("HTTP/1.1 301 Moved Permanently");
        header("Location:$loginUrl");
    }
    /*
     * 执行页面的私有业务
     * */
    static function page_business(){
        $fn='page_'.$GLOBALS['pageName'].'_business';
        if(function_exists($fn)){
            $fn();
        }
    }
    /*
    *初始化smarty
     * @params $response
     * */
    public static function smarty($response)
    {
        $smarty = new Smarty;
        $smarty->setCompileDir(R_ROOT . '/common/page_generator/smarty/templates/templates_c');  //模板的编译路径
        $smarty->setCompileDir(R_ROOT . '/common/page_generator/smarty/templates/cache');    //缓存路径  启用时需设置缓存时间和缓存设置为true
        $smarty->addPluginsDir(R_ROOT . '/common/page_generator/smarty/libs/customplugins');
        $smarty->setTemplateDir(TEMPLATE_PATH);
       // $smarty->setConfigDir(R_ROOT . '/cfg');
        $GLOBALS['smarty'] = $smarty;
        self::page_business();
        self::setSmartyVar($response);
    }
    /**
     * 判断是否是路由返回不同形式的值给web
     * @param $response
     *
     */
    public static function root($response)
    {
        if ($GLOBALS['isRoot']===true) {
            $list = array();
            $i=0;
            foreach ($response as $key => $val) {
                $i++;
                if(is_array(json_decode($val, true))){
                    $list[] = json_decode($val, true);
                    $response[$key]=json_decode($val, true);
                }else{
                    $list[]=$val;
                }
            }
            if ($i > 1) {
                $reStr=json_encode($response,true);

            }else{
                if(!empty($list[0])&&is_array($list[0])){
                    $reStr=json_encode($list[0], true);
                }else{
                    $reStr=!empty($list[0])?$list[0]:json_encode($list, true);
                }
            }
            exit($reStr);
        }
    }

    /*
      * 设置页面所需的samrty变量，setSmartyVar
      * @param $response
      * **/
    public static function setSmartyVar($response)
    {
        $GLOBALS['js_config']=array();
        $GLOBALS['css_config']=array();
        $js_config=array();
        if(file_exists(RESOURCES_CFG.'/js_config.php')){
            $js_config=include_once RESOURCES_CFG.'/js_config.php';
            empty($js_config[$GLOBALS['pageName']])?'':$GLOBALS['js_config']=$js_config[$GLOBALS['pageName']];
        }
        if(file_exists(RESOURCES_CFG.'/css_config.php')){
            $css_config=include_once RESOURCES_CFG.'/css_config.php';
            empty($css_config[$GLOBALS['pageName']])?'':$GLOBALS['css_config']=$css_config[$GLOBALS['pageName']];
        }
        $GLOBALS['smarty']->assign('js_config', $GLOBALS['js_config']);
        $GLOBALS['smarty']->assign('css_config', $GLOBALS['css_config']);
        foreach ($response as $key => $val) {
            if(is_array(json_decode($val, true))){
                $value = json_decode($val, true);
            }else{
                $value=$val;
            }
            $GLOBALS['smarty']->assign($key, $value);
        }

        $redis=$GLOBALS['redis'];
        $userInfo=$redis->get('userInfo');
        if($userInfo){
            $GLOBALS['smarty']->assign('userInfo', $userInfo);
        }else{
            $GLOBALS['smarty']->assign('userInfo', array('status'=>-1,'msg'=>'登陆失败','data'=>null));
        }
        $GLOBALS['smarty']->assign('ssoCfg', $GLOBALS['ssoCfg']);


        //设置其他的smarty变量
        $GLOBALS['smarty']->assign('STATIC_PRE', STATIC_PRE);
    }

    /*
     * 获取当页请求数据，getAllDate
     * 函数里的变量$rawdata，$origin_uri，$uri可直接在sourceCfg里的配置文件引用
     * @return array
     * **/
    public static function getAllDate()
    {
        $pageSource = array();
        $rawdata = '';
        $origin_uri = $_SERVER['REQUEST_URI'];
        $uriArr = explode('?', $_SERVER['REQUEST_URI']);
        $uri=$uriArr[0];
        $querystring = $_SERVER['QUERY_STRING'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty(file_get_contents("php://input"))) {
           // $rawdata = $GLOBALS['HTTP_RAW_POST_DATA'];
            $rawdata= file_get_contents("php://input");
            if(is_array(json_decode($rawdata, true))){
                $rawdataObj = json_decode($rawdata, true);
            }
        }
        foreach ($GLOBALS['evnCfg'] as $key => $val) {
            if (!file_exists($val['sourceCfg'])) continue;
            $sourceCfg = include_once $val['sourceCfg'];
            $sources = array();
            $page = array();
            $baseUrl = $val['baseUrl'];
            empty($sourceCfg['tpl']) ? '' : $sources = $sourceCfg['tpl'];
            if ($GLOBALS['isRoot'] === true) {
                empty($sourceCfg['router']) ? $sources = array() : $sources = $sourceCfg['router'];
            }
            if (isset($sources[$GLOBALS['pageName']])) {
                $page['baseUrl'] = $baseUrl;
                if ($GLOBALS['isRoot'] === true) {
                    foreach ($sources[$GLOBALS['pageName']] as $p => $src) {
                        if (empty($src['uri'])){
                            $src['uri'] = '/'.$GLOBALS['pageName'];
                            $sources[$GLOBALS['pageName']][$p]['uri']= '/'.$GLOBALS['pageName'] ;
                        }
                    }
                }
                $page['sources'] = $sources[$GLOBALS['pageName']];
                $pageSource[] = $page;
            }
        }
        return $pageSource;
    }

    /**
     * curl 并发
     * @param $cfg
     * @return array
     */
    public static function rolling_curl($cfgs)
    {
        $queue = curl_multi_init();
        $map = array();
        $temp = array();
        $urls = array();
        $pageName = $GLOBALS['pageName'];
        $timelimit = defined('TIMELIMIT') ? TIMELIMIT : self::$timelimit;
        foreach ($cfgs as $item => $value) {
            $baseUrl = $value['baseUrl'];
            $params = $value['sources'];
            foreach ($params as $key => $val) {
                $url='';
                if(is_array($baseUrl)){
                    $url = !empty($val['baseUrl']) ? $val['baseUrl'] : $baseUrl[mt_rand(0,count($baseUrl)-1)];
                }else{
                    $url = !empty($val['baseUrl']) ? $val['baseUrl'] : $baseUrl;
                }
                $url = $url . (empty($val['uri'])?'':$val['uri']);
                $datacode = $val['qs'];
                if (empty($val['method']) || strtoupper($val['method']) == 'GET') {
                    $query='';
                    if(is_array($datacode)){
                        foreach($datacode as $ky=>$ve){
                            $query=$query.$ky.'='.$ve.'&';
                        }
                    }
                    if (strpos($url, '?') === false) {
                        $url .= '?' . $query;
                    } else {
                        $url .= '&' . $query;
                    }
                    $datacode=$query;
                }
                is_array($datacode) ? $datacode = json_encode($datacode, JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) : '';
                $data_encode = rawurlencode($datacode);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timelimit);

                if (isset($val['method']) && strtoupper($val['method']) == 'POST') {  //post请求处理
                    $headers = array(
                        "Content-type: application/json"
                    );
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $datacode);
                    curl_setopt($ch, CURLOPT_NOBODY, FALSE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }

                curl_multi_add_handle($queue, $ch);
                //设置头信息的地方
                // $map[(string)$ch] = $val['param']['return'];
                $map [( string )$ch] = $key;
                $temp [$key] = $datacode;
                $urls[$key] = $url;
            }
        }


        $responses = array();
        do {
            while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM)
                ;
            if ($code != CURLM_OK) {
                break;
            }
            while ($done = curl_multi_info_read($queue)) {
                $info = curl_getinfo($done ['handle']); // 获得请求信息
                $error_info = curl_error($done ['handle']); // 获得错误信息
                $error_no = curl_errno($done ['handle']); // 获得错误信息
                $results = curl_multi_getcontent($done ['handle']); // 获得请求结果
                $responses [$map [( string )$done ['handle']]] = $results; // 获得的信息，重组数组
                $key = $map [( string )$done ['handle']];
                if (isset ($_GET ['debug']) && $_GET['debug'] == DEBUG_KEY) {
                    echo "请求接口：$urls[$key]<br>";
                    echo "请求参数：".$temp[$key] . ',';
                    echo "<br>";
                    echo '返回数据||' . $key . ':';
                    echo $results;
                    echo "<br>";
                    echo "<br>";
                }
                $responses [$key] = $results;
                curl_multi_remove_handle($queue, $done ['handle']);
                curl_close($done ['handle']);
                // 写日志
                //  self::write_log(json_encode($temp [$map [( string )$done ['handle']]], JSON_FORCE_OBJECT), $info, $error_info, $results);
            }
            if ($active > 0) {
                curl_multi_select($queue, 0.5);
            }
        } while ($active);
        curl_multi_close($queue);
        $GLOBALS ['response_data'] = $responses; // 后续业务需要
        return $responses;
    }

    /**
     * curl POST请求
     * @param $durl
     * @param array $data
     * @return mixed
     */
    public static function curl_post_contents($durl, $data = array(),$head=array())
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
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::$timelimit);
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
    public static function curl_get_contents($durl, $data = array(),$head=array())
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
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::$timelimit);
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
}
