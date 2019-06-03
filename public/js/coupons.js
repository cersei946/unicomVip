
/**
* wap端-优惠券
* author：wangbiguo
* */
$(function (){
	//基础数据
    var baseInfo = {
        
    };
    //参数
    var PAPM = {
 
    };

    
    function Coupons (){}
    Coupons.prototype.tabBarChange = function (){
        $('.nav-tabs').on('click','LI',function(){
            $(this).addClass('active').siblings('li').removeClass('active');
            $('.main_list li').eq( $(this).attr('data-key') ).addClass('checkedBox').siblings().removeClass('checkedBox');
        })
    }
    var coupons = new Coupons();
    var init = {
       coupons: function (){
        coupons.tabBarChange();
       }
    };
    // 初始化
    $.each(init,function (k){
        if (k) {
            init[k]();
        }
    });
    
});