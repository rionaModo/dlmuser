# dlweb

## 准备

首先请确保你已经拥有以下的一些环境。

*nodejs & npm*

我们默认你已经安装了[nodejs](https://nodejs.org/en/)，以及[npm](https://www.npmjs.com/)。

*php web server*

一个支持php的web服务器环境。这里我们使用`phpStudy`来提供web服务支持。

[phpStudy.net](http://www.phpstudy.net/);

或者从svn获得我们使用的版本，[phpStudy_2014_setup.1413444920.exe](http://192.168.100.50/svn/dev/%e5%b0%8f%e7%bb%84%e6%96%87%e6%a1%a3/%e5%89%8d%e7%ab%af%e7%bb%84/%e5%b7%a5%e7%a8%8b%e4%be%9d%e8%b5%96/dlmobile/phpStudy_2014_setup.1413444920.exe);

接下来关于php web server相关文件均可通过svn获取：[svn地址](http://192.168.100.50/svn/dev/%E5%B0%8F%E7%BB%84%E6%96%87%E6%A1%A3/%E5%89%8D%E7%AB%AF%E7%BB%84/%E5%B7%A5%E7%A8%8B%E4%BE%9D%E8%B5%96/dlmobile/)。

*frontend code*

前端代码工程化我们使用fis3，所以你也必须在全局下安装fis3，来使用命令行;

``` bash
git clone http://182.92.230.201:8000/dl_dev/dlmobile.git && cd dlmobile
npm install fis3 -g
```

## 安装及配置

`phpStudy`安装完成之后我需要进行一些简单的配置：

* 选择php版本

`phpStudy`/`其他选项`/`php版本切换` => `Nginx + PHP 5.5n`

* 配置Nginx

`phpStudy`/`其他选项`/`打开配置文件`/`vhosts-conf`

工程`./nginx.conf/`文件下的内容为nginx的配置,以上配置为工程必须配置。

* 本地微服务接口配置（不受git版本控制）

复制一份 `./site/evn_cfg/dev.php` 到当前文件夹，重命名为`cfg.php`用于配置你在coding时对与微服务接口地址及文件读取的配置。

`site`:站名

* 动态拓展

[php_igbinary.dll](http://192.168.100.50/svn/dev/%e5%b0%8f%e7%bb%84%e6%96%87%e6%a1%a3/%e5%89%8d%e7%ab%af%e7%bb%84/%e5%b7%a5%e7%a8%8b%e4%be%9d%e8%b5%96/dlmobile/dynamic-%20extensions/php_igbinary.dll);
[php_igbinary.pdb](http://192.168.100.50/svn/dev/%e5%b0%8f%e7%bb%84%e6%96%87%e6%a1%a3/%e5%89%8d%e7%ab%af%e7%bb%84/%e5%b7%a5%e7%a8%8b%e4%be%9d%e8%b5%96/dlmobile/dynamic-%20extensions/php_igbinary.pdb);
[php_redis.dll](http://192.168.100.50/svn/dev/%e5%b0%8f%e7%bb%84%e6%96%87%e6%a1%a3/%e5%89%8d%e7%ab%af%e7%bb%84/%e5%b7%a5%e7%a8%8b%e4%be%9d%e8%b5%96/dlmobile/dynamic-%20extensions/php_redis.dll);
[php_redis.pdb](http://192.168.100.50/svn/dev/%e5%b0%8f%e7%bb%84%e6%96%87%e6%a1%a3/%e5%89%8d%e7%ab%af%e7%bb%84/%e5%b7%a5%e7%a8%8b%e4%be%9d%e8%b5%96/dlmobile/dynamic-%20extensions/php_redis.pdb);

将上面4个文件放入`phpStudy/php55n/ext`路径下。

打开`phpStudy`/`其他选项`/`打开配置文件`/`php-ini`

找到 `Dynamic Extensions` 配置代码块，增加下面配置。

``` bash
; php_redis
extension=php_igbinary.dll
extension=php_redis.dll
```
## 使用

*tree view*

下面是工程的结构以及一些注释希望能够帮助你快速的了解她。
``` bash
dlweb:.
│  .gitignore
│  readme.md
│
├─.idea
│      workspace.xml
│
├─common
│  ├─businessCfg
│  │
│  ├─cfg
│  │
│  ├─common
│  │  │
│  │  └─class
│  │
│  └─page_generator
│      │
│      └─smarty
│          │
│          └─templates
│
├─danlu.com  #pc端相关资源
│  │
│  ├─business  #针对pc端存放私有后端业务逻辑
│  │
│  ├─cfg       #针对pc端 设置php全局变量
│  │
│  ├─evn_cfg   #针对pc端 根据环境设置相应的环境变量 ---将对应的环境命名文件重新复制一份并命名为cfg.php
│  │
│  ├─dlmain  #编译后的文件，如果不需要编译，可直接将静态资源存入该文件
│  │  └─resource
│  │      ├─css
│  │      │
│  │      ├─images
│  │      │
│  │      └─js
│  ├─resource #pc端的静态资源，样式采用CSS 预处理语言，所有需要进行编译
│  │  ├─css
│  │  │
│  │  ├─images
│  │  │
│  │  └─js
│  │
│  ├─sourceCfg  #js、css、数据资源配置
│  │
│  └─templates
│
├─m.danlu.com  # 文件目录与danlu.com一致，该文件主要用于存放用于移动端的相关资源
│
└─nginx.conf  #nginx文件配置备份


```

*develop*

``` bash
cd dlwebdemo/danlu.com
# 编译并监听文件变动
fis3 release -wl
```

fis3 默认使用less（推荐）来书写样式文件，当然你也可以书写原生css他们是兼容的。

`./sourceCfg/css_config.php` 配置需要在页面文档（body）头部部加载的css;
`./sourceCfg/js_config.php` 配置需要在页面文档（body）尾部加载的javascript;
`./templates` 书写模板;
`./resource` 书写网页源代码;


*example*
http://localhost或http://localhost/mextend/example;
http://localhost/mextend/loginexample;
## 写在最后

尽管我尽可能的写的详细，但是许多东西还是要自己去通过阅读源码来实现。have fun.
