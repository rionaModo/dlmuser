var fs = require("fs");
fis.config.set("project.ignore",[ 'node_modules/**',"mock/**","fis-conf.js",'*.php','*.tpl','dlmobile/**'])
var prod = fis.media("prod");


// 发布到 output文件夹中,并加上md5戳
//fis.set('project.files', '{*.js,*.less,*.png,*.gif,*.jpg}');
fis.set('project.files', 'resource/**');
var local_dilvery_dlmobile = [fis.plugin('local-deliver', {
    to: "./dlmobile"
})];


fis.match("*",{
    deploy: local_dilvery_dlmobile,
    useHash: false
}).match("*.less",{
    parser: fis.plugin('less',{
        'paths':[ [__dirname,"resource"].join("/") ]
    }),
    rExt:"css"
}).match('**.png', {
    optimizer: fis.plugin('png-compressor', {
        type : 'pngquant'
    }),
    useHash: false
});

var prod = fis.media("prod");
prod.match("*",{
    deploy: local_dilvery_dlmobile
}).match("**.js",{
    optimizer: fis.plugin("uglify-js",{
        sourceRoot:[__dirname,"resource"].join("/")
    }),
    rExt:"min.js"
}).match("*.less",{
    parser: fis.plugin('less',{
        'paths':[ [__dirname,"resource"].join("/") ]
    }),
    optimizer: fis.plugin("clean-css"),
    rExt:"min.css"
}).match("*.css",{
    optimizer: fis.plugin("clean-css"),
    rExt:"min.css"
})







