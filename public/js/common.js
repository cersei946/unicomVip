var HQMcommon = function (){

    /**
     * @desc   消息提示黑色弹窗
     * @author dinghuihua
     * @param  status { String } [可选][值为'success'则显示成功弹窗]
     */
    this.toast = function (txt, status) {
        if (!txt) return; // 禁止弹出undefined/null/空串
        var shield = document.createElement('DIV');
        shield.id = 'ajax_shield';
        shield.style.height = (document.documentElement.scrollHeight || document.body.scrollHeight) + 'px';
        var alertFram = document.createElement('DIV');
        alertFram.id = 'alertFram';
        if (status === 'success') {
            alertFram.className = 'success';
            alertFram.innerHTML = '<i class="icon-ok"></i><p>' + txt +'</p>';
        } else {
            alertFram.innerHTML = txt;
        }

        document.body.appendChild(alertFram);
        document.body.appendChild(shield);
        setTimeout(function () {
            $(alertFram).css({
                'opacity': 0.9,
                'transform': 'scale(1,1)',
                '-webkit-transform': 'scale(1,1)'
            });
        }, 100);
        setTimeout(function () {
            $(alertFram).css({
                'opacity': 0,
                'transform': 'scale(0.5,0.5)',
                '-webkit-transform': 'scale(0.5,0.5)'
            });
        },2000);
        setTimeout(function () {
            $(alertFram).remove();
            $(shield).remove();
        }, 2300);
        document.body.onselectstart = function () {
            return false;
        };
    };
}

var hqmCommon = new HQMcommon();
// hqmCommon.init();