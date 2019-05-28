
/**
* wap端-注册页面
* author：wangbiguo
* */
$(function (){
	//基础数据
    var baseInfo = {
        phone:'' ,// 手机号
        verify_code:'' ,// 验证码,
        channel: 0
    };
    //参数
    var PAPM = {
        // 校验手机号是否有效
        regPhoneNumber: /^1[34578]\d{9}$/,
        seconds: -1 
    };

    function Login (){}
    // 获取channel参数
    Login.prototype.getChannel = function (){
        var loginUrl = window.location.href
        if( loginUrl.indexOf('?') > -1 ){
            var loginUrlArr = loginUrl.split('?');
            if( loginUrlArr[1] && loginUrlArr[1].indexOf('=') > -1 ){
                var channel = loginUrlArr[1].split('=')[1] ? loginUrlArr[1].split('=')[1] : 0 ;
                // console.log(decodeURIComponent(channel) ? decodeURIComponent(channel) :channel);
                return decodeURIComponent(channel) ? decodeURIComponent(channel) :channel;
            }
        }
    }
    
    // 校验手机号
    Login.prototype.verifyPhone = function (){
        $('input[name=phoneNumber]').on('input', function () {
            baseInfo.phone = $(this).val();
            // 如果输入非数字，则删除输入的非数字
            if (/[^0-9]/.test(baseInfo.phone)) {
                $(this).val(baseInfo.phone.substring(0, baseInfo.phone.length - 1));
                baseInfo.phone = baseInfo.phone.substring(0, baseInfo.phone.length - 1);
            }
            // 如果手机号长度大于11，截取前11位
            if (baseInfo.phone.length > 11) {
                $(this).val(baseInfo.phone.substring(0, 11));
                baseInfo.phone = baseInfo.phone.substring(0, 11);
            }
            // 如果输入的是手机号，则可以点击获取验证码 
            if (PAPM.regPhoneNumber.test(baseInfo.phone)) {
                $('.yzm_text').addClass('canClickCode');
            } else {
                $('.yzm_text').removeClass('canClickCode');
            }
        });

        $('li').on('click','.canClickCode',function(){
            $('input[name=yzCode]').focus();
            // 参数
            var options = {
                phone: $.trim($('input[name=phoneNumber]').val()), //'手机号',
                
            };
            var data = {
                phoneNumber: baseInfo.phone
            }
            // console.log(data)
            // 调用接口
            hqmrequest.post('http://v.ocoun.com/admin/message/index',data,function(xhr){
                if(xhr.code == 200){
                    hqmCommon.toast('验证码发送成功');
                }else{
                    hqmCommon.toast(xhr.message);
                }
            })
            PAPM.seconds = 60; // 设置重新发送验证码的时长
            $('.yzm_text').removeClass('canClickCode');
            $('.yzm_text').text('重新发送(' + PAPM.seconds + ')');
            var timerYzm = window.setInterval(countDown, 1000);
            function countDown () {
                PAPM.seconds -= 1;
                if (PAPM.seconds >= 0) {
                    $('.yzm_text').removeClass('canClickCode');
                    $('.yzm_text').text('重新发送(' + PAPM.seconds + ')');
                } else {
                    $('.yzm_text').addClass('canClickCode');
                    $('.yzm_text').text('获取验证码');
                    window.clearInterval(timerYzm);
                }

            }
        })
    };
    // 校验验证码
    Login.prototype.verifyCode = function (){
        $('input[name=yzCode]').on('input', function () {
            baseInfo.verify_code = $(this).val();
            // 如果输入非数字，则删除输入的非数字
            if (/[^0-9]/.test(baseInfo.verify_code)) {
                $(this).val(baseInfo.verify_code.substring(0, baseInfo.verify_code.length - 1));
                baseInfo.verify_code = baseInfo.verify_code.substring(0, baseInfo.verify_code.length - 1);
            }
            // 如果验证码长度大于4，截取前4位
            if (baseInfo.verify_code.length > 4) {
                $(this).val(baseInfo.verify_code.substring(0, 4));
                baseInfo.verify_code = baseInfo.verify_code.substring(0, 4);
            }

        });
    }
    // agreement
    Login.prototype.agreeMent = function (){
        $('.agree_li span').on('click',function(){
            $(this).toggleClass('checked');
        })
    }
    // 点击注册
    Login.prototype.onSubmit = function (){
        $('input[type=button]').on('click',function (){
            var data1 = {
                phoneNumber: baseInfo.phone,
                verCode: baseInfo.verify_code
                // channel: login.getChannel()
            }

            // console.log(data1);
            if(!data1.phoneNumber){
                hqmCommon.toast('请输入正确的手机号！');
            } else if ( !data1.verCode ){
                hqmCommon.toast('验证码不正确！');
            } else if ( !$('.agree_li span').hasClass('checked') ){
                hqmCommon.toast('请阅读并勾选接受条款！');
            } else {
                // 调用接口
                hqmrequest.post('http://v.ocoun.com/index.php/admin/message/login',data1,function(xhr){
                    // console.log(xhr);
                    // console.log(typeof xhr);
                    if(xhr.code == 200){
                        hqmCommon.toast('注册成功');
                        window.location.href = "http://v.ocoun.com/index/jumplink/index.html";
                    }else{
                        hqmCommon.toast(xhr.message);
                    }
                })
            }
        })
    }
    
    var login = new Login();
    var init = {
        verify: function (){
            login.verifyPhone();
            login.verifyCode();
            login.agreeMent();
        },
        onsubmit: function (){
            login.onSubmit()
            
        }
    };
    // 初始化
    $.each(init,function (k){
        if (k) {
            init[k]();
        }
    });
    
});