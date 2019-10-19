/** glui_admin-v1.0.1
 * 源自 layui 案例 KITADMIN 

 */
var tab;
layui.define(['element', 'form', 'table'], function(exports) {
    var $ = layui.jquery,
        element = layui.element,
        layer = layui.layer,
        _win = $(window),
        _doc = $(document),
        _body = $('.glui-body'),
        form = layui.form,
        table = layui.table;
		
    var app = {
        hello: function(str) {
            layer.alert('Hello ' + (str || 'test'));
        },
        config: {
            type: 'page'
        },
        set: function(options) {
            var that = this;
            $.extend(true, that.config, options);
            return that;
        },
        init: function() {
            var that = this,
                _config = that.config;
                        },
                        renderAfter: function(elem) {
                            elem.find('li').eq(0).click(); //模拟点击第一个
            return that;
        }
    };

    //输出test接口
    exports('app', app);
});