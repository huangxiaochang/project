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

var baguetteBox = (function() {
    // SVG shapes used in buttons
    var leftArrow = '<svg width="40" height="60" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<polyline points="30 10 10 30 30 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"' +
              'stroke-linecap="butt" fill="none" stroke-linejoin="round">&lt;</polyline>' +
            '</svg>',
        rightArrow = '<svg width="40" height="60" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<polyline points="10 10 30 30 10 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"' +
              'stroke-linecap="butt" fill="none" stroke-linejoin="round">&gt;</polyline>' +
            '</svg>',
        closeX = '<svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" version="1.1">' +
            '<g stroke="rgb(160, 160, 160)" stroke-width="4">' +
            '<line x1="5" y1="5" x2="25" y2="25"/>' +
            '<line x1="5" y1="25" x2="25" y2="5"/>' +
            'X</g></svg>';
    // Main ID names
    var overlayID = 'baguetteBox-overlay';
    var sliderID = 'baguetteBox-slider';
    // Global options and their defaults
    var options = {}, defaults = {
        captions: true,
        buttons: 'auto',
        async: false,
        preload: 2,
        animation: 'slideIn'
    };
    // DOM Elements references
    var overlay, slider, previousButton, nextButton, closeButton;
    // Current image index inside the slider and displayed gallery index
    var currentIndex = 0, currentGallery = -1;
    // Touch event start position (for slide gesture)
    var touchStartX;
    // If set to true ignore touch events because animation was already fired
    var touchFlag = false;
    // Array of all used galleries (DOM elements)
    var galleries = [];
    // 2D array of galleries and images inside them
    var imagesMap = [];
    // Array containing temporary images DOM elements
    var imagesArray = [];

    // forEach polyfill for IE8
    if(!Array.prototype.forEach) {
        Array.prototype.forEach = function(callback, thisArg) {
            var len = this.length;
            for(var i = 0; i < len; i++) {
                callback.call(thisArg, this[i], i, this);
            }
        };
    }

    // Script entry point
    function run(selector, userOptions, upafter, imgslist) {
        buildOverlay();
        // For each gallery bind a click event to every image inside it
		if(imgslist){
			imglists = $('#imgslists li:not('+upafter+')').size();
			}else{
		    imglists = $('#imgslist li:not('+upafter+')').size();
			}
        galleries = document.querySelectorAll(selector);
        [].forEach.call(
            galleries,
            function (galleryElement, galleryIndex) {
                var galleryID = imagesMap.length;
                imagesMap.push(galleryElement.getElementsByTagName('a'));
                imagesMap[galleryID].options = userOptions;
                [].forEach.call(
                    imagesMap[galleryID],
                    function (imageElement, imageIndex) {
						if(upafter){
							imageIndex = imglists-1;
							galleryID = 0;
							}
						bind(imageElement, 'click', function(event) {
                            /*jshint -W030 */
                            event.preventDefault ? event.preventDefault() : event.returnValue = false;
                            prepareOverlay(galleryID);
                            showOverlay(imageIndex);
                        });
                    }
                );
            }
        );
        defaults.transforms = testTransformsSupport();
    }

    function buildOverlay() {
        overlay = document.getElementById(overlayID);
        // Check if the overlay already exists
        if(overlay) {
            slider = document.getElementById(sliderID);
            previousButton = document.getElementById('previous-button');
            nextButton = document.getElementById('next-button');
            closeButton = document.getElementById('close-button');
            return;
        }
        // Create overlay element
        overlay = document.createElement('div');
        overlay.id = overlayID;
        document.getElementsByTagName('body')[0].appendChild(overlay);
        // Create gallery slider element
        slider = document.createElement('div');
        slider.id = sliderID;
        overlay.appendChild(slider);
        // Create all necessary buttons
        previousButton = document.createElement('button');
        previousButton.id = 'previous-button';
        previousButton.innerHTML = leftArrow;
        overlay.appendChild(previousButton);

        nextButton = document.createElement('button');
        nextButton.id = 'next-button';
        nextButton.innerHTML = rightArrow;
        overlay.appendChild(nextButton);

        closeButton = document.createElement('button');
        closeButton.id = 'close-button';
        closeButton.innerHTML = closeX;
        overlay.appendChild(closeButton);

        previousButton.className = nextButton.className = closeButton.className = 'baguetteBox-button';

        bindEvents();
    }

    function bindEvents() {
        // When clicked on the overlay (outside displayed image) close it
        bind(overlay, 'click', function(event) {
            if(event.target && event.target.nodeName !== "IMG")
                hideOverlay();
        });
        // Add event listeners for buttons
        bind(document.getElementById('previous-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            showPreviousImage();
        });
        bind(document.getElementById('next-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            showNextImage();
        });
        bind(document.getElementById('close-button'), 'click', function(event) {
            /*jshint -W030 */
            event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
            hideOverlay();
        });
        // Add touch events
        bind(overlay, 'touchstart', function(event) {
            // Save x axis position
            touchStartX = event.changedTouches[0].pageX;
        });
        bind(overlay, 'touchmove', function(event) {
            if(touchFlag)
                return;
            /*jshint -W030 */
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            touch = event.touches[0] || event.changedTouches[0];
            if(touch.pageX - touchStartX > 40) {
                touchFlag = true;
                showPreviousImage();
            } else if (touch.pageX - touchStartX < -40) {
                touchFlag = true;
                showNextImage();
            }
        });
        bind(overlay, 'touchend', function(event) {
            touchFlag = false;
        });
        // Activate keyboard shortcuts
        bind(document, 'keydown', function(event) {
            switch(event.keyCode) {
                case 37: // Left arrow
                    showPreviousImage();
                    break;
                case 39: // Right arrow
                    showNextImage();
                    break;
                case 27: // Esc
                    hideOverlay();
                    break;
            }
        });
    }

    function prepareOverlay(galleryIndex) {
        // If the same gallery is being opened prevent from loading it once again
        if(currentGallery === galleryIndex)
            return;
        currentGallery = galleryIndex;
        // Update gallery specific options
        setOptions(imagesMap[galleryIndex].options);
        // Empty slider of previous contents (more effective than .innerHTML = "")
        while(slider.firstChild)
            slider.removeChild(slider.firstChild);
        imagesArray.length = 0;
        // Prepare and append images containers
        for(var i = 0; i < imagesMap[galleryIndex].length; i++) {
            imagesArray.push(returnImageContainer());
            slider.appendChild(imagesArray[i]);
        }
    }

    function setOptions(newOptions) {
        if(!newOptions)
            newOptions = {};
        for(var item in defaults) {
            options[item] = defaults[item];
            if(typeof newOptions[item] !== 'undefined')
                options[item] = newOptions[item];
        }
        /* Apply new options */
        // Change transition for proper animation
        slider.style.transition = slider.style.webkitTransition = options.animation === 'fadeIn' ? 'opacity .4s ease' : '';
        // Hide buttons if necessary
        if(options.buttons === 'auto' && ('ontouchstart' in window || imagesMap[currentGallery].length === 1))
            options.buttons = false;
        // Set buttons style to hide or display them
        previousButton.style.display = nextButton.style.display = options.buttons ? '' : 'none';
    }

    // Return DOM element for image container <div class="full-image">...</div>
    function returnImageContainer() {
        var fullImage = document.createElement('div');
        fullImage.className = 'full-image';
        return fullImage;
    }

    function showOverlay(index) {
        // Return if overlay is already visible
        if(overlay.style.display === 'block')
            return;
        // Set current index to a new value and show proper image
        currentIndex = index;
        loadImage(currentIndex, function() {
            preloadNext(currentIndex);
            preloadPrev(currentIndex);
        });
        updateOffset();
        overlay.style.display = 'block';
        // Fade in overlay
        setTimeout(function() {
            overlay.className = 'visible';
        }, 50);
    }

    function hideOverlay() {
        // Return if overlay is already hidden
        if(overlay.style.display === 'none')
            return;
        // Fade out and hide the overlay
        overlay.className = '';
        setTimeout(function() {
            overlay.style.display = 'none';
        }, 500);
    }

    function loadImage(index, callback) {
        var imageContainer = imagesArray[index];
        // If index is invalid return
        if(typeof imageContainer === 'undefined')
            return;
        // If image is already loaded run callback and return
        if(imageContainer.getElementsByTagName('img')[0]) {
            if(callback)
                callback();
            return;
        }
        // Get element reference, optional caption and source path
        imageElement = imagesMap[currentGallery][index];
        imageCaption = imageElement.getAttribute('data-caption') || imageElement.title;
        imageSrc = getImageSrc(imageElement);
		var imageSrc = ''+imageSrc+'';
		if(imageSrc.indexOf('.thumb.') != -1) { imageSrc = imageSrc.replace('.thumb.jpg', '');}
        // Prepare image container elements
        var figure = document.createElement('figure');
        var image = document.createElement('img');
        var figcaption = document.createElement('figcaption');
        imageContainer.appendChild(figure);
        // Add loader element
        figure.innerHTML = '<div class="spinner">' +
            '<div class="double-bounce1"></div>' +
            '<div class="double-bounce2"></div>' +
            '</div>';
        // Set callback function when image loads
        image.onload = function() {
            // Remove loader element
            var spinner = this.parentNode.querySelector('.spinner');
            this.parentNode.removeChild(spinner);
            if(!options.async && callback)
                callback();
        };
        image.setAttribute('src', imageSrc);
        figure.appendChild(image);
        // Insert caption if available
        if(options.captions && imageCaption) {
            figcaption.innerHTML = imageCaption;
            figure.appendChild(figcaption);
        }
        // Run callback
        if(options.async && callback)
            callback();
    }

    function getImageSrc(image) {
        // Set dafult image path from href
        var result = image;
		
        // If dataset is supported find the most suitable image
        if(image.dataset) {
            var srcs = [];
            // Get all possible image versions depending on the resolution 
            for(var item in image.dataset) {
                if(item.substring(0, 3) === 'at-' && !isNaN(item.substring(3)))
                    srcs[item.replace('at-', '')] = image.dataset[item];
            }
            // Sort resolutions ascending
            keys = Object.keys(srcs).sort(function(a, b) {
                return parseInt(a) < parseInt(b) ? -1 : 1;
            });
            // Get real screen resolution 
            var width = window.innerWidth * window.devicePixelRatio;
            // Find first image bigger than or equal to the current width
            for(var i = 0; i < keys.length; i++) {
                if(keys[i] >= width) {
                    result = srcs[keys[i]];    
                    break;
                }
                result = srcs[keys[i]];
            }
        }
        return result;
    }

    function showNextImage() {
        if(currentIndex <= imagesArray.length - 2) {
            currentIndex++;
            updateOffset();
            preloadNext(currentIndex);
        } else {
            slider.className = 'bounce-from-right';
            setTimeout(function() {
                slider.className = '';
            }, 400);
        }
    }

    function showPreviousImage() {
        if(currentIndex >= 1) {
            currentIndex--;
            updateOffset();
            preloadPrev(currentIndex);
        } else {
            slider.className = 'bounce-from-left';
            setTimeout(function() {
                slider.className = '';
            }, 400);
        }
    }

    function updateOffset() {
        var offset = -currentIndex * 100 + '%';
        if(options.animation === 'fadeIn') {
            slider.style.opacity = 0;
            setTimeout(function() {
                /*jshint -W030 */
                options.transforms ?
                    slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
                    : slider.style.left = offset;
                slider.style.opacity = 1;
            }, 400);
        } else {
            /*jshint -W030 */
            options.transforms ?
                slider.style.transform = slider.style.webkitTransform = 'translate3d(' + offset + ',0,0)'
                : slider.style.left = offset;
        }
    }

    function testTransformsSupport() {
        var div = document.createElement('div'),
            support = false;
        support = typeof div.style.perspective !== 'undefined' || typeof div.style.webkitPerspective !== 'undefined';
        return support;
    }

    function preloadNext(index) {
        if(index - currentIndex >= options.preload)
            return;
        loadImage(index + 1, function() { preloadNext(index + 1); });
    }

    function preloadPrev(index) {
        if(currentIndex - index >= options.preload)
            return;
        loadImage(index - 1, function() { preloadPrev(index - 1); });
    }

    function bind(element, event, callback) {
        if(element.addEventListener)
            element.addEventListener(event, callback, false);
        else
            element.attachEvent('on' + event, callback);
    }

    return {
        run: run
    };

})();

$.fn.extend({
  Progress: function(options){
    var settings = {
      width: 90, // 进度条宽度
      height: 20, // 进度条高度
      percent: 0, // 当前占比
      backgroundColor: '#555', // 进度条背景颜色
      barColor: '#d9534f', // 进度条颜色
      fontColor: '#fff', // 百分比字体颜色
      radius: 4, // 边角圆度
      fontSize: 12, // 字体大小
      increaseTime: 1000.00/60.00,
      increaseSpeed: 1, // 每次调整，增长速度；只有在animate为true的情况下有效
      animate: true // 调整的时候，是否使用动画增长，默认为true
    };
    $.extend(settings, options);

    var $svg = $(this), $background, $bar, $g, $text, timeout;

    function progressPercent(p){
      if(!$.isNumeric(p) || p < 0) {
        return 0;
      } else if(p > 100) {
        return 100;
      } else {
        return p;
      }
    }        

    // 动画相关方法
    var Animate = {
      getWidth: function(){
        // 获取当前的宽度，根据总宽度和percent
        return settings.width * settings.percent / 100.00;
      },
      getPercent: function(currentWidth){
        // 根据当前的宽度，计算当前的percent
        return parseInt((100 * currentWidth / settings.width).toFixed(2));
      },
      animateWidth: function(currentWidth, targetWidth){
        // 动画增长
        timeout = setTimeout(function(){
          if(currentWidth > targetWidth) {
            if(currentWidth - settings.increaseSpeed <= targetWidth) {
              currentWidth = targetWidth;
            } else {
              currentWidth = currentWidth - settings.increaseSpeed;
            }
          } else if(currentWidth < targetWidth) {
            if(currentWidth + settings.increaseSpeed >= targetWidth) {
              currentWidth = targetWidth;
            } else {
              currentWidth = currentWidth + settings.increaseSpeed;
            }
          }

          $bar.attr("width", currentWidth);
          $text.empty().append(Animate.getPercent(currentWidth) + "%");

          if(currentWidth != targetWidth) {
            Animate.animateWidth(currentWidth, targetWidth);
          }
        }, settings.increaseTime); 
      }
    }

    function svg(tag){
      return document.createElementNS("http://www.w3.org/2000/svg", tag);
    }

    // 初始化条件
    !!function(){
      settings.percent = progressPercent(settings.percent);

      $svg.attr({'width': settings.width, 'height': settings.height});

      $background = $(svg("rect")).appendTo($svg)
                      .attr({x: 0, rx: settings.radius, width: settings.width, height: settings.height, fill: settings.backgroundColor});

      $bar = $(svg("rect")).appendTo($svg)
                .attr({x: 0, rx: settings.radius, height: settings.height, fill: settings.barColor});

      $g = $(svg("g")).appendTo($svg)
                .attr({"fill": "#fff", "text-anchor": "middle", "font-family": "DejaVu Sans,Verdana,Geneva,sans-serif", "font-size": settings.fontSize});

      $text = $(svg("text")).appendTo($g)
                .attr({"x": settings.width/2.0, "y": settings.height/2.0 + settings.fontSize/3.0, fill: settings.fontColor});

      draw();
    }();

    // 绘制进度条
    function draw() {
      var targetWidth = Animate.getWidth();

      // 是否使用动画增长
      if(settings.animate) {
        if(timeout) {
          clearTimeout(timeout);
        }
        var currentWidth = parseFloat($bar.attr("width"));
        if(!currentWidth) currentWidth = 0;

        Animate.animateWidth(currentWidth, targetWidth);
      } else {
        $bar.attr("width", targetWidth);
        $text.empty().append(settings.percent + "%");
      }
    }

    this.percent = function (p) {
      if(p) {
        p = progressPercent(p);

        settings.percent = p;
        draw();
      }
      return settings.percent;
    }

    return this;
  }
});