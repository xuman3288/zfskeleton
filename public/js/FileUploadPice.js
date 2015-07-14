/**
 * Created by xuman on 15-7-14.
 */
/**
 * Created by xuman on 14-12-11.
 */
function uploadInit(jQuery, func) {
    var $ = function (id) {
        return document.getElementById(id);
    };

// 一些元素
    var eleForm = $("form"), eleFile = $("file"), eleSubmit = $("uploadBtn"),
// 文件等待上传列表的容器
        eleUploadUl = $("uploadUl"),
// 模板元素
        eleTemplate = $("fileTemplate");
    eleSubmit.style.visibility = "hidden";
    eleUploadUl.style.display = "none";
// 上传文件队列数组
    var fileArray = [],
// 文件分割上传的间隙大小
        fileSplitSize = 1024 * 2000,
// 模板HTML
        htmlTemplate = eleTemplate && eleTemplate.innerHTML || '';
    if (typeof history.pushState == "function") {
        // 一些元素的状态
        var objStateElement = (function () {
            var _$ = function (name, fileid) {
                return $("file" + name + "_" + fileid) || { innerHTML: "" };
            };

            return {
                // 上传进度条背景的控制
                backgroundSize: function (params, percent) {
                    var dom = typeof params == "string" ? $("filelist_" + params) : params;
                    if (dom) {
                        dom.style.mozBackgroudSize = percent + "% 100%";
                        dom.style.backgroundSize = percent + "% 100%";
                    }
                },
                wait: function (fileid) {
                    // 一些状态的改变
                    _$("status", fileid).innerHTML = '<span class="uploading">上传中...</span>';
                    _$("operate", fileid).innerHTML = '<a href="javascript:" data-type="pause" data-id="' + fileid + '">暂停</a>';
                },
                keep: function (fileid) {
                    _$("status", fileid).innerHTML = '<span class="waiting">等待续传...</span>';
                },
                success: function (fileid, time) {
                    var eleList = $("filelist_" + fileid),
                        eleOperate = $("fileoperate_" + fileid),
                        eleStatus = $("filestatus_" + fileid);

                    // 进度条隐藏
                    this.backgroundSize(eleList, "0");

                    // 因为元素不会删除，因此，有必要清除id
                    eleStatus.id = "";
                    eleOperate.id = "";
                    eleList.id = "";
                    // 删除本地存储的起点，完全上传成功，因此没有续传的必要
                    localStorage.removeItem(fileid);
                    // 状态信息再变化
                    eleStatus.innerHTML = '<span class="success">' + ((performance.now() - time > 1000) ? "上" : "秒") + '传成功！</span>';
                    eleOperate.innerHTML = '';

                },
                error: function (fileid, error) {
                    error && alert(error);
                    // 状态信息再变化
                    _$("status", fileid).innerHTML = '<span class="error">出现异常！</span>';
                    _$("operate", fileid).innerHTML = '<a href="javascript:" data-type="try" data-id="' + fileid + '">重试</a>';
                }
            }
        })();

        // 单文件上传
        var funFileUpload = function (fileid, onsuccess, onerror, onpause) {
            var file = fileArray[fileid], now = performance.now();
            if (!fileid || !file) {
                window.isUploading = false;
                return;
            }
            onsuccess = onsuccess || function (id, json) {
                jQuery("#game_select").trigger('change');
                //funFileUpload(fileArray[0]);
            };
            onerror = onerror || function () {
                funFileUpload(fileArray[fileArray.indexOf(fileid) + 1]);
            };
            onpause = onpause || function () {
                funFileUpload(fileArray[fileArray.indexOf(fileid) + 1]);
            };
            if (file.flagPause == true) {
                onpause.call(fileid);
                return;
            }
            objStateElement.wait(fileid);
            // 文件分割上传
            // 文件大小和分割起点
            // 注释的是本地存储实现
            var size = file.size, start = localStorage[fileid] * 1 || 0; //start = $("filelist_" + fileid).filesize;
            if (size == start) {
                // 已经传过了
                fileArray.shift();
                delete fileArray[fileid];
                objStateElement.success(fileid, now);
                // 回调
                onsuccess.call(fileid, {});
                return;
            }


            var funFileSize = function () {
                if (file.flagPause == true) {
                    onpause.call(fileid);
                    return;
                }
                var data = new FormData();
                data.append("name", encodeURIComponent(file.name));
                data.append("fileid", fileid);
                data.append("file", file.slice(start, start + fileSplitSize));
                data.append("start", start + "");
                data.append("totalsize", size);
                data.append("id", jQuery("#game_select").val());
                // XMLHttpRequest 2.0 请求
                var xhr = new XMLHttpRequest();
                xhr.open("post", eleForm.action, true);
                //xhr.setRequestHeader("X_Requested_With", location.href.split("/")[5].replace(/[^a-z]+/g,"$"));
                xhr.setRequestHeader("X-Request-With", "XMLHttpRequest");
                // 上传进度中
                xhr.upload.addEventListener("progress", function (e) {
                    objStateElement.backgroundSize(fileid, (e.loaded + start) / size * 100);
                }, false);
                // ajax成功后
                xhr.onreadystatechange = function (e) {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            try {
                                var json = JSON.parse(xhr.responseText);
                            } catch (e) {
                                objStateElement.error(fileid);
                                return;
                            }
                            //var json = JSON.parse(xhr.responseText);
                            if (!json || !json.succ) {
                                objStateElement.error(fileid, json.error);
                                onerror.call(fileid, json);
                                return;
                            }

                            if (start + fileSplitSize >= size) {
                                // 超出，说明全部分割上传完毕
                                // 上传队列中清除者一项
                                //func(fileid, JQuery);
                                fileArray.shift();
                                delete fileArray[fileid];
                                objStateElement.success(fileid, now);
                                // 回调
                                onsuccess.call(fileid, json);
                            } else {
                                // 尚未完全上传完毕
                                // 存储上传成功的文件点，以便出现意外的时候，下次可以断点续传
                                localStorage.setItem(fileid, start + "");
                                // 改变下一部分文件的起点位置
                                start += fileSplitSize;
                                //console.log(start);
                                // 上传下一个分割文件
                                funFileSize();
                            }
                        } else {
                            objStateElement.error(fileid);
                        }
                    }
                };

                xhr.send(data);
            };

            // 文件分割上传开始
            funFileSize();
        };

        // IE10+, Chrome, FireFox等~
        eleSubmit.addEventListener("click", function (event) {
            if (window.isUploading) {
                return;
            }
            window.isUploading = true;
            funFileUpload(fileArray[0]);

        });

        // 选择文件后
        eleFile.addEventListener("change", function (event) {
            window.isUploading = false;
            fileArray = [];
            // 获取文件
            var files = event.target.files;
            // 遍历文件，显示文件列表信息
            var htmlFile = '', index = 0, length = files.length;
            var file = files[index];
            // console.log(file.type);
            var name = file.name, size = file.size, type = file.type || "", id = (file.lastModifiedDate + "").replace(/\W/g, '') + size + type.replace(/\W/g, '');
            var objHtml = {
                id: id,
                type: 'cloud',
                name: name,
                size: size + "B",
                status: '<span class="waiting">等待上传</span>',
                operate: '<a href="javascript:" data-type="delete" data-id="' + id + '">删除</a>'
            }
            // name 50字符限制
            if (name.length > 50) {
                objHtml.name = '<span title="' + name + '">' + name.slice(0, 20) + '...' + name.slice(-20) + '</span>';
            }

            // 文件类型与对应的图标
            var format = name.split(".");
            objHtml.type = format[format.length - 1] || "unknown";

            // 如果大小大于1M使用'M'为单位表示, 1位小数点
            if (size > 1024 * 1024) {
                objHtml.size = Math.round(size / (1024 * 1024) * 10) / 10 + "M";
            } else if (size > 1024) {
                // 如果大小大于1KB使用'KB'为单位表示, 1位小数点
                objHtml.size = Math.round(size / 1024 * 10) / 10 + "KB";
            }

            if (size > 1024 * 1024 * 1024) {
                // 如果文件大于200K, 状态为'大小溢出'
                objHtml.id = Math.random();
                objHtml.status = '<span class="error">文件过大</span>';
                objHtml.operate = '';
            } else if (fileArray.indexOf(id) != -1) {
                // 如果文件已经在列表中
                objHtml.id = Math.random();
                objHtml.status = '<span class="error">文件已存在</span>';
                objHtml.operate = '';
            } else {
                // 加入文件队列，并缓存对应的文件二进制对象
                fileArray.push(id);
                fileArray[id] = file;
            }

            htmlFile = htmlTemplate.replace(/\$\w+\$/gi, function (matchs) {
                var returns = objHtml[matchs.replace(/\$/g, "")];
                return (returns + "") == "undefined" ? "" : returns;
            });


            // 载入HTML
            //var os = JQuery("select[name=os]").val();
            if (htmlFile !== '') {
                eleSubmit.style.visibility = "visible";
                eleUploadUl.style.display = "table";
                jQuery('.filelist_class').remove();
                eleUploadUl.insertAdjacentHTML("beforeEnd", htmlFile);

                // 初始化进度条

                // 下面注释的是本地存储方法
                fileArray.forEach(function (fileid) {
                    var loaded = localStorage[fileid] * 1;
                    if (loaded > 0) {
                        objStateElement.backgroundSize(fileid, loaded / fileArray[fileid].size * 100);
                    }
                });

                // 现在直接使用Ajax请求
                var nameArray = fileArray.map(function (fileid) {
                    if (fileArray[fileid]) {
                        var nameSplit = fileArray[fileid].name.split("."),
                            name = nameSplit[nameSplit.length - 1];

                        return fileid + "." + name;
                    }
                    return '';

                });
                var xhr_filesize = new XMLHttpRequest();
                xhr_filesize.open("GET", "filesize?filename=" + nameArray.join(), true);
                xhr_filesize.onreadystatechange = function (e) {
                    if (xhr_filesize.readyState == 4) {
                        if (xhr_filesize.status == 200 && xhr_filesize.responseText) {
                            var json = JSON.parse(xhr_filesize.responseText);
                            if (json.succ && json.data) {
                                for (var key in json.data) {
                                    if (json.data[key] > 0 && json.data[key] < fileArray[key].size) {
                                        objStateElement.backgroundSize(key, json.data[key] / fileArray[key].size * 100);
                                        objStateElement.keep(key);
                                    } else if (json.data[key] > 0 && json.data[key] >= fileArray[key].size) {
                                        //objStateElement.backgroundSize(key, json.data[key] / fileArray[key].size * 100);
                                        //delete fileArray[key];
                                        //objStateElement.success(key, performance.now() - 2000);
                                    }
                                    if ($("filelist_" + key)) $("filelist_" + key).filesize = json.data[key];

                                }
                            }
                        }
                    }
                };
                xhr_filesize.send();
            }
            eleForm.reset();
        });
        // 文件删除等委托事件
        eleUploadUl.addEventListener("click", function (event) {
            var target = event.target, id = target && target.getAttribute("data-id");
            if (id && /^a$/i.test(target.tagName)) {
                switch (target.getAttribute("data-type")) {
                    case "delete":
                    {
                        var filelist = $("filelist_" + id);
                        if (filelist) {
                            filelist.style.opacity = 0;

                            // 源数据清除
                            fileArray.splice(fileArray.indexOf(id), 1);
                            delete fileArray[id];
                            //if (delete fileArray[id]) console.log(fileArray.join() + "---删除成功");

                            setTimeout(function () {
                                filelist.parentNode.removeChild(filelist);
                                if (fileArray.length == 0) {
                                    eleSubmit.style.visibility = "hidden";
                                    eleUploadUl.style.display = "none";
                                }
                            }, 220);
                        }
                        break;
                    }
                    case "pause":
                    {
                        var eleStatus = $("filestatus_" + id);
                        if (fileArray[id]) {
                            fileArray[id].flagPause = true;
                            target.setAttribute("data-type", "reupload");
                            target.innerHTML = "继续";
                            if (eleStatus) eleStatus.innerHTML = "上传暂停";
                        }
                        break;
                    }
                    case "try":
                    case "reupload":
                    {
                        var eleStatus = $("filestatus_" + id);
                        if (fileArray[id]) {
                            fileArray[id].flagPause = false;
                            target.setAttribute("data-type", "pause");
                            target.innerHTML = "暂停";
                            if (eleStatus) eleStatus.innerHTML = "上传中...";
                            funFileUpload(id);
                        }

                    }
                }
            }
        });

    } else {
        eleUploadUl.style.display = "block";
        eleUploadUl.innerHTML = '<li class="error"><p style="margin:.5em 1em;">当前浏览器不支持！试试IE10+, Chrome等~</p></li>';
    }
};