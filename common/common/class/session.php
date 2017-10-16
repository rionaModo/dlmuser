<?php
/**
 * Created by PhpStorm.
 * User: danlu
 * Date: 2016/12/7
 * Time: 14:36
 */

class sSession{
    private $redis='';
    private $isCollect=0;
    private $time=5;//重连redis
    public function __construct($param)
    {
        session_start();
        $this->collect_redis($param);

    }
    public function collect_redis($array){
        do{
            if($this->isCollect||(isset($GLOBALS['redis'])&& $GLOBALS['redis']===1)){
               $GLOBALS['redis']=1;
                break;
            }
            if(class_exists('Redis')){
                $this->redis=new Redis();
                $this->isCollect=$this->redis->connect($array['IP'],$array['PORT']);
                $this->time--;
                if(!$this->isCollect&&$this->time==0){
                    $this->isCollect=false;
                    break;
                  //  echo 'redis链接失败!IP:'.$array['IP'].',PORT:'.$array['PORT'];
                }
            }else{
                $this->isCollect=false;
             //   echo '采用session存储';
                break;
            }
        }while($this->time);
    }
    public function set($key,$value,$timeOut=0){
        $val='';
        $k=session_id().'_'.$key;
        if(is_object($value)||is_array($value)){
            $val=json_encode($value);
        }else{
            $val=$value;
        }
        if(!$this->isCollect){
            return $_SESSION[$k]=$val;
        }
        $retRes = $this->redis->set($k, $val);
        if ($timeOut > 0)
            $retRes->expire($k, $timeOut);
        return $retRes;
    }
    public function get($key){
        $k=session_id().'_'.$key;
        if(!$this->isCollect){
            $result= empty($_SESSION[$k])?null:$_SESSION[$k];
        }else{
            $result = trim($this->redis->get($k));
        }
        if(json_decode($result)){
            $result=json_decode($result,true);
        }
        return $result;
    }
    public function delete($key){
        $k=session_id().'_'.$key;
        if(!$this->isCollect){
            unset($_SESSION[$k]);
        }else{
            $this->redis->delete($k);
        }

    }
}