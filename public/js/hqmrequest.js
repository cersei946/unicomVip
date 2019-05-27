// var cityCode = '';
// var platformType = '2';
// var uid = '';

var hqmrequest = {
    'post': function (url, data, callback) {
        
        // data.appName = zgConfig.appName;
        // data.platformType = zgConfig.platformType;
        // data.city = data.city ? data.city : zgConfig.cityCode;
        // data.uid = data.uid ? data.uid : zgConfig.uid;
       
        $.ajax({
            'url': url,
            'type': 'post',
            'data': data,
            success: function (xhr) {
                if ($.isFunction(callback)) {
                    callback(xhr);
                }
            },
            error: function (xhr){
                console.log(xhr);
            }
        });
    },
    'get': function (url, data, callback) {
        // data.appName = zgConfig.appName;
        // data.platformType = zgConfig.platformType;
        // data.city = data.city ? data.city : zgConfig.cityCode;
        // data.uid = data.uid ? data.uid : zgConfig.uid;

        $.ajax({
            'url': url,
            'type': 'get',
            'data': data,
            success: function (xhr) {
                if ($.isFunction(callback)) {
                    callback(xhr);
                }
            },
            error: function (){
                callback({'code': 404, 'message': '接口请求失败'});
            }
        });
    }
};
