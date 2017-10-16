<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2017/4/19
 * Time: 16:41
 */

class apiInterface{
    public function __construct()
    {
        if(empty($_GET['api'])){
            return;
        }
        $api=$_GET['api'];
        $propertyName='pri_'.$api.'_fun';
        if(method_exists($this, $propertyName)){
            $this->$propertyName();
        }
    }
    /*
     * 退出登录
     * */
    public  function pri_logout_fun(){
        if(!empty($_GET['sso_return'])&& $_GET['sso_return']== 1){
            return header('Location: /');
        }
        if(!isset($GLOBALS['redis'])){
            $GLOBALS['redis']=new sSession(array("IP"=>REDIS_IP,"PORT"=>REDIS_PORT));
        }
       $GLOBALS['redis']->delete('userInfo');
        $reLocURL=getSsoreUrl(array('sso_return'=>1));
    //    die('Location: '.$GLOBALS['ssoCfg']['logout'].'?service='.$reLocURL);
        header('Location: '.$GLOBALS['ssoCfg']['logout'].'?service='.$reLocURL);
        return;
    }
}

