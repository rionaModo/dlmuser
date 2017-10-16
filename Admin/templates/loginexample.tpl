<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit" />
    <title>丹露红包大抽奖</title>
    {include './part/addCss.tpl'}
    <script>
        var userInfo={$userInfo|json_encode:JSON_UNESCAPED_UNICODE}
        var ACT_INFO = {$ACT_INFO|json_encode:JSON_UNESCAPED_UNICODE}
    </script>
</head>
<body>
 this is a demo
{include './part/addJs.tpl'}
</body>
</html>
