<script>
    var SYS_JS_COURCE=[];

    {if $smarty.const.STATIC_FROM=='yes'}
        {foreach from=$js_config  key=key item=item}
        SYS_JS_COURCE.push('{$STATIC_PRE|cat:$item|regex_replace:'/\.js/':'.min.js'}?{file_createtime path='/Admin'|cat:$STATIC_PRE|cat:$item|regex_replace:'/\.js/':'.min.js'}');
        {/foreach}
    {else}
        {foreach from=$js_config  key=key item=item}
        SYS_JS_COURCE.push('{$STATIC_PRE|cat:$item}?{file_createtime path='/Admin'|cat:$STATIC_PRE|cat:$item}');
        {/foreach}
    {/if}

   var STATIC_PRE='{$STATIC_PRE}';
    var _paq = _paq || [];
</script>

{literal}
<script type="text/javascript">

    "use strict"
    /**
     * _initScript 创造script标签，加载js
     * */
    function _initScript(srcs, callback){
        var isArray = false;
        var loadCount = 0;
        var doLoad = function(){
            if (Object.prototype.toString.apply(srcs) === "[object Array]"){
                if(srcs.length == 0){
                    if(callback){
                        callback();
                    }
                    return;
                }
                isArray = true;
                for(var i = 0, len = srcs.length; i < len; i++){
                    createScriptObj(srcs[i]);
                }
            }else{
                if(!srcs){
                    if(callback){
                        callback();
                    }
                    return;
                }
                createScriptObj(srcs);
            }

        };
        var createScriptObj = function(src){
            var scriptObj = document.createElement('script');
            scriptObj.type = 'text/javascript';
            scriptObj.src = src;

            if (scriptObj.readyState){ //IE
                scriptObj.onreadystatechange = function(){
                    if (scriptObj.readyState == "loaded" || scriptObj.readyState == "complete"){
                        scriptObj.onreadystatechange = null;
                        loadCount++;
                        if(!isArray || (isArray && loadCount == srcs.length)){
                            if(callback){
                                callback();
                            }
                        }
                    }
                };
            } else { //Others: Firefox, Safari, Chrome, and Opera
                scriptObj.onload = function(){
                    loadCount++;
                    if(!isArray || (isArray && loadCount == srcs.length)){
                        if(callback){
                            callback();
                        }
                    }
                };
            }
            document.getElementsByTagName("body")[0].appendChild(scriptObj);
        };
        setTimeout(doLoad, 0);
    }
    /**
     * 将所有的操作放在dom加载之后
     * */
    function addLoadEvent(func) {
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = function(){
                func();
            };
        } else {
            window.onload = function() {
                oldonload();
                func();
            }
        }
    };

    (function(){
        addLoadEvent(function(){
            window.SYS_JS_COURCE=window.SYS_JS_COURCE||[];
            var i= 0,len=SYS_JS_COURCE.length;
            function addScript(callback){
                _initScript(SYS_JS_COURCE[i],function(){
                    if(i<len){
                        i++;
                        addScript()
                    }else{
                        callback&&callback();
                    }
                })
            }
            addScript();
        });
    })()
    ;
</script>
{/literal}