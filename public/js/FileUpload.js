/**
 * Created by xuman on 15-7-14.
 */
/*<div class="cover">
</div>
<div class="alert"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div></div></div>
<style>
.cover{
    position: absolute;
    top:0px;
    left:0px;
    width: 100%;
    background-color: rgba(111, 111, 111, 0.8);
    z-index: 10000;
    display: none;
    }
.alert{
    position: absolute;
    width: 500px;
    height: 300px;
    background-color: rgb(111, 111, 0);
    top:50%;
    left:50%;
    margin-left: -250px;
    margin-top: -150px;
    z-index: 10001;
    padding-top: 30px;
    display: none;

    }
</style>*/
var FileUpload = {

    progress :function(loaded, size){
        var pp = Math.floor(loaded/size * 100) + "%";
        $(this.progressBar).width(pp);
        $(this.progressBar).html(pp);
    },
    showAll : function()
    {
        this.showCover();
        this.showAlert();
    },
    hideAll :function(){
        this.hideCover();
        this.hideAlert()
    },
    sendFile: function (url, file, formdata, onSuccess, onError) {
        var me = this;
        try {
            var xhr = new XMLHttpRequest();
        } catch (e) {
            var xhr = ActiveXObject("Msxml12.XMLHTTP");
        }
        // 上传进度中
        xhr.upload.addEventListener("progress", function (e) {
            me.progress(e.loaded,file.size);
            console.log(e);
        }, false);
        xhr.onreadystatechange = function (e) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    var retText = xhr.responseText;
                    var ret = JSON.parse(retText);
                    onSuccess && onSuccess(ret);
                } else {
                    onError && onError();
                }
            }
        }
        xhr.open("POST", url, true);
        xhr.setRequestHeader("X-Request-With", "XMLHttpRequest");
        var fd = new FormData();
        fd.append("file", file);
        if (formdata) {
            for (key in formdata) {
                fd.append(key, formdata[key]);
            }
        }
        this.showAll();
        xhr.send(fd);

    },
    splitSize: 1024 * 100,
    splitSend: function (file) {
        var me = this, size = file.size, name = file.name, type = file.type || "";
        var fileId = (file.lastModifiedDate + "").replace(/\W/g, '') + size + type.replace(/\W/g, '');
        var start = localStorage[fileId] * 1 || 0;
        var funcSendPiece = function () {
            var data = {
                tsize: size,
                start: start,
                fileId: fileId
            };
            me.sendFile(
                'url',
                file.slice(start, start + me.splitSize),
                data,
                function (ret) {
                    if (ret.code == 0) {
                        if (start + me.splitSize >= size) {
                            //over
                            localStorage.removeItem(fileId)
                        } else {
                            start = start + this.splitSize;
                            localStorage.setItem(fileId, start + "");
                            funcSendPiece();
                        }
                    }
                }
            );
        }
    },
    cover :null,
    createCover :function()
    {
        var cover = $('<div></div>');
        cover.css({
            "position": "absolute",
            "top":"0px",
            "left":"0px",
            "width": "100%",
            "background-color": "rgba(111, 111, 111, 0.5)",
            "z-index": 10000,
            "display": "none",
            "height" : window.innerHeight + "px"
        });
        $("body").append(cover);
        return this.cover = cover;
    },
    showCover :function()
    {
        if(this.cover)
        {
            this.cover.show();
        } else {
            this.createCover().show();
        }
    },
    hideCover :function()
    {
        if(this.cover)
        {
            this.cover.hide();
        }
    },
    progressBar : null,
    alert : null,
    createAlertProgressBar :function(){//基于bootstrap样式
        var alert = $('<div class="alert"></div>');
        alert.css({
            "position": "absolute",
            "width": "500px",
            "height": "200px",
            "background-color": "rgb(111, 111, 0)",
            "top":"50%",
            "left":"50%",
            "margin-left": "-250px",
            "margin-top": "-150px",
            "z-index": "10001",
            //"padding-top": "5px",
            "display": "none"
        });
        alert.append('<h4 style="text-align: center;">文件上传进度</h4>')
        alert.append('<div class="progress"><div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div></div>');
        alert.find('.progress').css({
            'margin-top' : "50px"
        });
        $("body").append(alert);
        this.progressBar = alert.find('.progress-bar');
        return this.alert = alert;

    },
    showAlert :function(){
        if(this.alert)
        {
            this.alert.show();
        }else {
            this.createAlertProgressBar().show();
        }
    },
    hideAlert :function(){
        if(this.alert)
        {
            this.alert.hide();
        }
    }

};