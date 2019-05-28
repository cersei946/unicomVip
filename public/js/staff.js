
/**
* wap端-联通推广员个人中心
* author：wangbiguo
* */
$(function (){

	//基础数据
    var baseInfo = {
        
    };
    //参数
    var PAPM = {
 
    };
    var triggerEvent = "touchstart";


    function staffCenter (){}
    // 监听mask滚动
    staffCenter.prototype.listenMask = function (){
        $('.mask').on('touchmove',function (event){
            event.preventDefault(); 
        })
    }
    // 链接提示弹框弹出
    staffCenter.prototype.linkShow = function (){
        $('.link').on('click',function(){
            $('.bounced_link').removeClass('display_none');

        })

    }
    // 链接提示弹框关闭
    staffCenter.prototype.linkHidden = function (){
        $('.bounced_link .close').on('click',function(){
            $('.bounced_link').addClass('display_none');

        })
        $('.bounced_link .cancel').on('click',function(){
            $('.bounced_link').addClass('display_none');

        })
    }
    // 复制链接
    staffCenter.prototype.linkmain = function (){
        $('.bounced_link .copy').on('click',function(){
            var copymain = document.getElementById('linkmain');
            // iphone设备
            if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)){
                window.getSelection().removeAllRanges();
                var range = document.createRange();
                // 选中需要复制的节点
                range.selectNode(copymain);
                // 执行选中元素
                window.getSelection().addRange(range);
                // 执行 copy 操作
                var successful = document.execCommand('copy');
                // 移除选中的元素
                window.getSelection().removeAllRanges();
                hqmCommon.toast('复制完毕，去粘贴');
                staff.linkHidden();
            } else {
                // 选择对象
                copymain.select();
                document.execCommand("Copy"); // 执行浏览器复制命令
                hqmCommon.toast('复制完毕，去粘贴');
                staff.linkHidden();
            }
        })
    }
    // 显示二维码
    staffCenter.prototype.showQrCode = function (){
        $('.myQrcode').on('click',function(){
            $('.bounced_vercode').removeClass('display_none');
        })
    }
    // 隐藏二维码
    staffCenter.prototype.hiddenQrCode = function (){
        $('.bounced_vercode .close').on('click',function(){
            $('.bounced_vercode').addClass('display_none');
        })
    }
    // 保存二维码
    staffCenter.prototype.saveQrCode = function (){
        $('.bounced_vercode .save_code').on('click',function(){
            staff.savePic();
        })
    }

    //保存到相册
    staffCenter.prototype.savePic = function (){         
        var picurl= $("#picurl").attr("src");
        //alert(picurl);
        savePicture(picurl);
    }
    function savePicture(Url){
        var blob=new Blob([''], {type:'application/octet-stream'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = Url;
        a.download = Url.replace(/(.*\/)*([^.]+.*)/ig,"$2").split("?")[0];
        var e = document.createEvent('MouseEvents');
        e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        a.dispatchEvent(e);
        URL.revokeObjectURL(url);
    }






    var staff = new staffCenter();
    var init = {
        staffFn: function (){
            staff.linkShow();
            staff.linkHidden();
            staff.linkmain();
            staff.hiddenQrCode();
            staff.showQrCode();
            staff.saveQrCode();
            staff.listenMask();
       }
    };
    // 初始化
    $.each(init,function (k){
        if (k) {
            init[k]();
        }
    });
    
});