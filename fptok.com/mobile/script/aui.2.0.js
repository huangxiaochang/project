/**
 * aui-popup.js
 * @author 流浪男
 * @todo more things to abstract, e.g. Loading css etc.
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
(function( window, undefined ) {
    "use strict";
    var auiPopup = function() {
        this._init();
    };
    var	CLASS_MASK = "aui-mask",
    	CLASS_MASK_IN = 'aui-mask-in',
    	CLASS_MASK_OUT = 'aui-mask-out',
        CLASS_POPUP = 'aui-popup',
    	CLASS_POPUP_IN = 'aui-popup-in',
    	CLASS_POPUP_OUT = 'aui-popup-out',
    	CLASS_POPUP_FOR = 'aui-popup-for';
    var	__MASK = '.'+CLASS_MASK,
    	__MASK_IN = '.'+CLASS_MASK_IN,
    	__MASK_OUT = '.'+CLASS_MASK_OUT,
        __POPUP = '.'+CLASS_POPUP,
    	__POPUP_IN = '.'+CLASS_POPUP_IN,
    	__POPUP_OUT = '.'+CLASS_POPUP_OUT;
    var popupStatus = false;
    auiPopup.prototype = {
        _init: function() {
        	var self = this;
        	var _btn = document.querySelectorAll("["+CLASS_POPUP_FOR+"]");
        	if(_btn){
        		for(var i=0;i<_btn.length;i++){
        			_btn[i].setAttribute("tapmode", "");
        			_btn[i].onclick = function(e){
        				var popupId = this.getAttribute(CLASS_POPUP_FOR);
        				var popupDom = document.getElementById(popupId);
        				if(popupDom){
							if(popupDom.className.indexOf(CLASS_POPUP_IN) > -1 || document.querySelector(__POPUP_IN)){
					            self.hide(popupDom);
					        }else{
					        	self.show(popupDom);
					        }
        				}else{
        					return;
        				}
					}
        		}
        	}
        },
        show: function(el){
        	var self = this;
        	if(el.className.indexOf(CLASS_POPUP_IN) > -1 || document.querySelector(__POPUP_IN)){
	            self.hide(el);
	            return;
	        }
            if(popupStatus) return;
        	if(!document.querySelector(__MASK)){
				var maskHtml = '<div class="aui-mask"></div>';
				document.body.insertAdjacentHTML('beforeend', maskHtml);
			}
        	el.style.display = "block";
        	setTimeout(function(){
        		document.querySelector(__MASK).classList.add(CLASS_MASK_IN);
	            el.classList.add(CLASS_POPUP_IN);
                popupStatus = true;
	        }, 10)
	        document.querySelector(__MASK).addEventListener("touchstart", function(event){
	        	event.preventDefault();
	        	self.hide(el);
	        })
            el.addEventListener("touchmove", function(event){
                event.preventDefault();
            },false)
        },
        hide: function(el){
            if(!popupStatus) return;
        	document.querySelector(__MASK).classList.remove(CLASS_MASK_IN);
        	document.querySelector(__MASK).classList.add(CLASS_MASK_OUT);
        	if(!document.querySelector(__POPUP_IN))return;
            document.querySelector(__POPUP_IN).classList.add(CLASS_POPUP_OUT);
            document.querySelector(__POPUP_IN).classList.remove(CLASS_POPUP_IN);
	        setTimeout(function(){
                if(!document.querySelector(__POPUP_OUT))return;
	        	document.querySelector(__POPUP_OUT).style.display = "none";
	            document.querySelector(__POPUP_OUT).classList.remove(CLASS_POPUP_OUT);
	            if(document.querySelector(__MASK)){
					document.querySelector(__MASK).parentNode.removeChild(document.querySelector(__MASK));
				}
                popupStatus = false;
	        }, 300)
        }
    };
	window.auiPopup = auiPopup;
})(window);

(function( window, undefined ) {
    "use strict";
    var auiDialog = function() {
    };
    var isShow = false;
    auiDialog.prototype = {
        params: {
            title:'',
            msg:'',
            buttons: ['取消','确定'],
            input:false
        },
        create: function(params,callback) {
        	var self = this;
            var dialogHtml = '';
            var buttonsHtml = '';
            var headerHtml = params.title ? '<div class="aui-dialog-header">' + params.title + '</div>' : '<div class="aui-dialog-header">' + self.params.title + '</div>';
            if(params.input){
                params.text = params.text ? params.text: '';
                var msgHtml = '<div class="aui-dialog-body"><input type="text" placeholder="'+params.text+'"></div>';
            }else{
                var msgHtml = params.msg ? '<div class="aui-dialog-body">' + params.msg + '</div>' : '<div class="aui-dialog-body">' + self.params.msg + '</div>';
            }
            var buttons = params.buttons ? params.buttons : self.params.buttons;
            if (buttons && buttons.length > 0) {
                for (var i = 0; i < buttons.length; i++) {
                    buttonsHtml += '<div class="aui-dialog-btn" tapmode button-index="'+i+'">'+buttons[i]+'</div>';
                }
            }
            var footerHtml = '<div class="aui-dialog-footer">'+buttonsHtml+'</div>';
            dialogHtml = '<div class="aui-dialog">'+headerHtml+msgHtml+footerHtml+'</div>';
            document.body.insertAdjacentHTML('beforeend', dialogHtml);
            // listen buttons click
            var dialogButtons = document.querySelectorAll(".aui-dialog-btn");
            if(dialogButtons && dialogButtons.length > 0){
                for(var ii = 0; ii < dialogButtons.length; ii++){
                    dialogButtons[ii].onclick = function(){
                        if(callback){
                            if(params.input){
                                callback({
                                    buttonIndex: parseInt(this.getAttribute("button-index"))+1,
                                    text: document.querySelector("input").value
                                });
                            }else{
                                callback({
                                    buttonIndex: parseInt(this.getAttribute("button-index"))+1
                                });
                            }
                        };
                        self.close();
                        return;
                    }
                }
            }
            self.open();
        },
        open: function(){
            if(!document.querySelector(".aui-dialog"))return;
            var self = this;
            document.querySelector(".aui-dialog").style.marginTop =  "-"+Math.round(document.querySelector(".aui-dialog").offsetHeight/2)+"px";
            if(!document.querySelector(".aui-mask")){
                var maskHtml = '<div class="aui-mask"></div>';
                document.body.insertAdjacentHTML('beforeend', maskHtml);
            }
            // document.querySelector(".aui-dialog").style.display = "block";
            setTimeout(function(){
                document.querySelector(".aui-dialog").classList.add("aui-dialog-in");
                document.querySelector(".aui-mask").classList.add("aui-mask-in");
                document.querySelector(".aui-dialog").classList.add("aui-dialog-in");
            }, 10)
            document.querySelector(".aui-mask").addEventListener("touchmove", function(e){
                e.preventDefault();
            })
            document.querySelector(".aui-dialog").addEventListener("touchmove", function(e){
                e.preventDefault();
            })
            return;
        },
        close: function(){
            var self = this;
            document.querySelector(".aui-mask").classList.remove("aui-mask-in");
            document.querySelector(".aui-dialog").classList.remove("aui-dialog-in");
            document.querySelector(".aui-dialog").classList.add("aui-dialog-out");
            if (document.querySelector(".aui-dialog:not(.aui-dialog-out)")) {
                setTimeout(function(){
                    if(document.querySelector(".aui-dialog"))document.querySelector(".aui-dialog").parentNode.removeChild(document.querySelector(".aui-dialog"));
                    self.open();
                    return true;
                },200)
            }else{
                document.querySelector(".aui-mask").classList.add("aui-mask-out");
                document.querySelector(".aui-dialog").addEventListener("webkitTransitionEnd", function(){
                    self.remove();
                })
                document.querySelector(".aui-dialog").addEventListener("transitionend", function(){
                    self.remove();
                })
            }
        },
        remove: function(){
            if(document.querySelector(".aui-dialog"))document.querySelector(".aui-dialog").parentNode.removeChild(document.querySelector(".aui-dialog"));
            if(document.querySelector(".aui-mask")){
                document.querySelector(".aui-mask").classList.remove("aui-mask-out");
            }
            return true;
        },
        alert: function(params,callback){
        	var self = this;
            return self.create(params,callback);
        },
        prompt:function(params,callback){
            var self = this;
            params.input = true;
            return self.create(params,callback);
        }
    };
	window.auiDialog = auiDialog;
})(window);

(function( window, undefined ) {
    "use strict";
    var auiToast = function() {
        // this.create();
    };
    var isShow = false;
    auiToast.prototype = {
        create: function(params,callback) {
            var self = this;
            var toastHtml = '';
            switch (params.type) {
                case "success":
                    var iconHtml = '<i class="aui-iconfont aui-icon-correct"></i>';
                    break;
                case "fail":
                    var iconHtml = '<i class="aui-iconfont aui-icon-close"></i>';
                    break;
                case "custom":
                    var iconHtml = params.html;
                    break;
                case "loading":
                    var iconHtml = '<div class="dt-toast-loading"></div>';
                    break;
            }

            var titleHtml = params.title ? '<div class="aui-toast-content">'+params.title+'</div>' : '';
            toastHtml = '<div class="aui-toast">'+iconHtml+titleHtml+'</div>';
            if(document.querySelector(".aui-toast"))return;
            document.body.insertAdjacentHTML('beforeend', toastHtml);
            var duration = params.duration ? params.duration : "2000";
            self.show();
            if(params.type == 'loading'){
                if(callback){
                    callback({
                        status: "success"
                    });
                };
            }else{
                setTimeout(function(){
                    self.hide();
                }, duration)
            }
        },
        show: function(){
            var self = this;
            document.querySelector(".aui-toast").style.display = "block";
            document.querySelector(".aui-toast").style.marginTop =  "-"+Math.round(document.querySelector(".aui-toast").offsetHeight/2)+"px";
            if(document.querySelector(".aui-toast"))return;
        },
        hide: function(){
            var self = this;
            if(document.querySelector(".aui-toast")){
                document.querySelector(".aui-toast").parentNode.removeChild(document.querySelector(".aui-toast"));
            }
        },
        remove: function(){
            if(document.querySelector(".aui-dialog"))document.querySelector(".aui-dialog").parentNode.removeChild(document.querySelector(".aui-dialog"));
            if(document.querySelector(".aui-mask")){
                document.querySelector(".aui-mask").classList.remove("aui-mask-out");
            }
            return true;
        },
        success: function(params,callback){
            var self = this;
            params.type = "success";
            return self.create(params,callback);
        },
        fail: function(params,callback){
            var self = this;
            params.type = "fail";
            return self.create(params,callback);
        },
        custom:function(params,callback){
            var self = this;
            params.type = "custom";
            return self.create(params,callback);
        },
        loading:function(params,callback){
            var self = this;
            params.type = "loading";
            return self.create(params,callback);
        }
    };
    window.auiToast = auiToast;
})(window);

/**
 * aui-scroll.js
 * @author Beck && 流浪男
 * @todo more things to abstract, e.g. Loading css etc.
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
(function(window) {
	'use strict';
	var isToBottom = false,isMoved = false;
	var auiScroll = function (params,callback) {
		this.extend(this.params, params);
		this._init(params,callback);
	}
	auiScroll.prototype = {
		params: {
			listren:false,
            distance: 100
        },
		_init : function(params,callback) {
			var self = this;
			if(self.params.listen){
				document.body.addEventListener("touchmove", function(e){
					self.scroll(callback);
				});
				document.body.addEventListener("touchend", function(e){
					self.scroll(callback);
				});
			}
			window.onscroll = function(){
				self.scroll(callback);
			}
		},
		scroll : function (callback) {
			var self = this;
			var clientHeight = document.documentElement.scrollTop === 0 ? document.body.clientHeight : document.documentElement.clientHeight;
			var scrollTop = document.documentElement.scrollTop === 0 ? document.body.scrollTop : document.documentElement.scrollTop;
			var scrollHeight = document.documentElement.scrollTop === 0 ? document.body.scrollHeight : document.documentElement.scrollHeight;

			if (scrollHeight-scrollTop-self.params.distance <= window.innerHeight) {
	        	isToBottom = true;
	        	if(isToBottom){
	        		callback({
	        			"scrollTop":scrollTop,
	        			"isToBottom":true
	        		})
	        	}
	        }else{
	        	isToBottom = false;
	        	callback({
        			"scrollTop":scrollTop,
        			"isToBottom":false
        		})
	        }
		},
        extend: function(a, b) {
			for (var key in b) {
			  	if (b.hasOwnProperty(key)) {
			  		a[key] = b[key];
			  	}
		  	}
		  	return a;
		}
	}
	window.auiScroll = auiScroll;
})(window);

/*! echo-js v1.7.3 | (c) 2016 @toddmotto | https://github.com/toddmotto/echo */
!function(t,e){"function"==typeof define&&define.amd?define(function(){return e(t)}):"object"==typeof exports?module.exports=e:t.echo=e(t)}(this,function(t){"use strict";var e,n,o,r,c,a={},u=function(){},d=function(t){return null===t.offsetParent},l=function(t,e){if(d(t))return!1;var n=t.getBoundingClientRect();return n.right>=e.l&&n.bottom>=e.t&&n.left<=e.r&&n.top<=e.b},i=function(){(r||!n)&&(clearTimeout(n),n=setTimeout(function(){a.render(),n=null},o))};return a.init=function(n){n=n||{};var d=n.offset||0,l=n.offsetVertical||d,f=n.offsetHorizontal||d,s=function(t,e){return parseInt(t||e,10)};e={t:s(n.offsetTop,l),b:s(n.offsetBottom,l),l:s(n.offsetLeft,f),r:s(n.offsetRight,f)},o=s(n.throttle,250),r=n.debounce!==!1,c=!!n.unload,u=n.callback||u,a.render(),document.addEventListener?(t.addEventListener("scroll",i,!1),t.addEventListener("load",i,!1)):(t.attachEvent("onscroll",i),t.attachEvent("onload",i))},a.render=function(n){for(var o,r,d=(n||document).querySelectorAll("[data-echo], [data-echo-background]"),i=d.length,f={l:0-e.l,t:0-e.t,b:(t.innerHeight||document.documentElement.clientHeight)+e.b,r:(t.innerWidth||document.documentElement.clientWidth)+e.r},s=0;i>s;s++)r=d[s],l(r,f)?(c&&r.setAttribute("data-echo-placeholder",r.src),null!==r.getAttribute("data-echo-background")?r.style.backgroundImage="url("+r.getAttribute("data-echo-background")+")":r.src!==(o=r.getAttribute("data-echo"))&&(r.src=o),c||(r.removeAttribute("data-echo"),r.removeAttribute("data-echo-background")),u(r,"load")):c&&(o=r.getAttribute("data-echo-placeholder"))&&(null!==r.getAttribute("data-echo-background")?r.style.backgroundImage="url("+o+")":r.src=o,r.removeAttribute("data-echo-placeholder"),u(r,"unload"));i||a.detach()},a.detach=function(){document.removeEventListener?t.removeEventListener("scroll",i):t.detachEvent("onscroll",i),clearTimeout(n)},a});