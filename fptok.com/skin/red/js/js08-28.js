//广告切换
$(document).ready(function(){
	var Ban=$('.ban');
	var Ul=$('.ban ul');
	var Li=$('.ban ul li');
	var numLi=$('.ban ol li');
	var time=5000;
	var Left=$('.banLeft');
	var Right=$('.banRight');
	var i=0;
	var timeId=null;
	numLi.hover(function(){
	var index=$(this).index();
	$(this).addClass('hover').siblings().removeClass('hover');
	Ul.animate({'left':-Width*index},0);
	});		
	var Width=Li.width();	
	function Banner(){
		if(i==numLi.length-1){
			i=0;
			}else{
				i++;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0,function(){
					if(i==0){
						Ul.css('left',0);
						}
					});
		}	
	function banRight(){
		if(i==0){
			i=numLi.length-1;
			}else{
				i--;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0);
		}		
	timeId=setInterval(Banner,time);
	Ban.hover(function(){
		clearInterval(timeId);
			Left.show();
			Right.show();
		},function(){
			timeId=setInterval(Banner,time);
			Left.hide();
			Right.hide();
			});	
			
	Right.click(function(){
		clearInterval(timeId);
		Banner();
		});	
		
	Left.click(function(){
		clearInterval(timeId);
		banRight();
		});	
	});
//快速找房
$(document).ready(function(){
	$('.qy-box li').hover(function(){
		$(this).children('span').addClass('hover');
		$(this).children('dl').show();
		},function(){
			$(this).children('span').removeClass('hover');
			$(this).children('dl').hide();
			});
	});
//新盘测评
$(document).ready(function(){
	$('.main-tr-cp li').hover(function(){
		$(this).children('a').css('margin-top',-80);
		},function(){
			$(this).children('a').css('margin-top',-32);
			});
	});	
//优惠团购切换
$(document).ready(function(){
	var dlBox=$('.main-tg-tt');
	var dlWidth=$('.main-tg-tt dl').outerWidth(true);
	var size=$('.main-tg-tt dl').length;
	var num=Math.ceil(size/4);
	var num1=Math.floor(size/4);
	var n=4;
	var m=n-(size-num1*n);
	var i=0;
	var Left=$('.main-tg-left');
	var Right=$('.main-tg-right');
	Left.click(function(){
		if(i==0){
			i==0;
			}else{
				i--;
				}
			dlBox.animate({'left':-dlWidth*i*4},500);
			Right.show();
			if(i==0){
				Left.hide();
				}	
		});
	Right.click(function(){
		i++	
		if(i==num-1){
			dlBox.animate({'left':-(dlWidth*i*n-dlWidth*m)},500);
			Right.hide();
			}else{
				dlBox.animate({'left':-dlWidth*i*n},500);
				}
			Left.show();	
		});	
	
	});
//买新房Tab切换
$(document).ready(function(){
	$('.main-xf-nav li').hover(function(){
		var i=$(this).index();
		$(this).addClass('hover').siblings().removeClass('hover');
		$('.main-xf-con').eq(i).show().siblings('.main-xf-con').hide();
		});
	});
//房产专题切换
$(document).ready(function(){
	var Ban=$('.main-zt');
	var Ul=$('.main-zt ul');
	var Li=$('.main-zt ul li');	
	var Width=$('.main-zt li').outerWidth(true);
	var size=$('.main-zt li').length;
	var time=3000;
	var num=Math.ceil(size/4);
	var num1=Math.floor(size/4);
	var n=4;
	var m=n-(size-num1*n);
	var i=0;
	var Left=$('.main-zt-left');
	var Right=$('.main-zt-right');
	var timeId=null;
	
	function Banner(){
		if(i==size-4){
			i=0;
			}else{
				i++;
				}
				Ul.animate({'left':-Width*i},0,function(){
					if(i==0){
						Ul.css('left',0);
						}
					});
		}	
	function banRight(){
		if(i==0){
			i=size-4;
			}else{
				i--;
				}
				Ul.animate({'left':-Width*i},0);
		}		
	timeId=setInterval(Banner,time);
	Ban.hover(function(){
		clearInterval(timeId);
			Left.show();
			Right.show();
		},function(){
			timeId=setInterval(Banner,time);
			Left.hide();
			Right.hide();
			});	
			
	Right.click(function(){
		clearInterval(timeId);
		Banner();
		});	
		
	Left.click(function(){
		clearInterval(timeId);
		banRight();
		});	
		
	});
//资讯广告切换
$(document).ready(function(){
	var Ban=$('.main-zx-tr');
	var Ul=$('.main-zx-tr ul');
	var Li=$('.main-zx-tr ul li');
	var numLi=$('.main-zx-tr ol li');
	var Width=Li.width();
	var time=4000;
	var Left=$('.main-zx-left');
	var Right=$('.main-zx-right');
	var i=0;
	var timeId=null;
	
	numLi.hover(function(){
	var index=$(this).index();
	$(this).addClass('hover').siblings().removeClass('hover');
	Ul.animate({'left':-Width*index},0);
	});		
	function Banner(){
		if(i==numLi.length-1){
			i=0;
			}else{
				i++;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0,function(){
					if(i==0){
						Ul.css('left',0);
						}
					});
		}	
	function banRight(){
		if(i==0){
			i=numLi.length-1;
			}else{
				i--;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0);
		}		
	timeId=setInterval(Banner,time);

	Right.click(function(){
		clearInterval(timeId);
		Banner();
		});	
	Left.click(function(){
		clearInterval(timeId);
		banRight();
		});	
	});
//团购报名
$(document).ready(function(){
    $('.main-tg-bm').click(function(){
	   $('.tgPop-box').show();
	   });
	$('.tgClose').click(function(){
		$('.tgPop-box').hide();
		});	    
});

//团购侧栏滚动
$(document).ready(function(){
	var oul = $('.tg3-bb ul');
	var oulHtml = oul.html();
	oul.html(oulHtml+oulHtml)
	var timeId = null;	
	var speed = 2;
	function slider(){
		if(oul.css('top')==-224+'px'){
		oul.css('top',0);
		}else{
			oul.css('top','+=-2px');
			}	
	 	}
	// setInterval()函数的作用是：每隔一段时间，执行该函数里的代码
	 timeId = setInterval(slider,100);

	$('.tg3-bb').hover(function(){
		clearInterval(timeId);
	},function(){
		timeId = setInterval(slider,30);
		});
	});
//楼盘测评报名
$(document).ready(function(){
	$('.newcp-bm').click(function(){
		$('.apply_form').show();
		});
	$('.pop-form-close').click(function(){
		$('.apply_form').hide();
		});	
	});
//别墅列表筛选
$(document).ready(function(){
	var p1=$('.bs-list dd li');
	var w1=0;
	var w3=0;
	$('.bs-list dd li').eq(1).find('p').css({'width':365,'margin-left':-120});
	$('.bs-list dd li').eq(2).find('p').css({'width':182,'margin-left':-75.5});
	
	p1.hover(function(){
		var i=$(this).index();
		var name1=$(this).children('span');
		var name2=$(this).children('p');
		
		name1.addClass('hover');
		$(this).siblings('li').children('span').removeClass('hover');
		name2.addClass('hover');
		$(this).siblings('li').children('p').removeClass('hover');

		name2.children('a').each(function(){
			w3=w3+$(this).outerWidth(true);
			});
		w2=w3+20;
		w4=w3+10;
		if(i==1){
			name2.css({'width':w2,'margin-left':-120});
			}else{
				name2.css({'width':w4,'margin-left':-(w3-20)/2});
				}
		
		w3=0;
		event.stopPropagation();	
				
		});
	});
//房产众筹内页效果图切换
$(document).ready(function(){
	var Ban=$('.fczc-ban');
	var Ul=$('.fczc-ban ul');
	var Li=$('.fczc-ban ul li');
	var numLi=$('.fczc-ban ol li');
	var Width=Li.width();
	var time=4000;
	var Left=$('.fczc-left');
	var Right=$('.fczc-right');
	var i=0;
	var timeId=null;
	numLi.hover(function(){
	var index=$(this).index();
	$(this).addClass('hover').siblings().removeClass('hover');
	Ul.animate({'left':-Width*index},0);
	});		
	function Banner(){
		if(i==numLi.length-1){
			i=0;
			}else{
				i++;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0,function(){
					if(i==0){
						Ul.css('left',0);
						}
					});
		}	
	function banRight(){
		if(i==0){
			i=numLi.length-1;
			}else{
				i--;
				}
				numLi.eq(i).addClass('hover').siblings().removeClass('hover');
				Ul.animate({'left':-Width*i},0);
		}		
	timeId=setInterval(Banner,time);

	Right.click(function(){
		clearInterval(timeId);
		Banner();
		});	
	Left.click(function(){
		clearInterval(timeId);
		banRight();
		});	
	});
/*房产众筹报名*/
$(document).ready(function(){
	
	$(window).scroll(function(){
		var thisTop=$(this).scrollTop();
		if(thisTop>126){
			$('.fczc-bm-box').css({'position':'fixed','top':10});
			}else{
				$('.fczc-bm-box').css({'position':'absolute','top':30});
				}
		});		
	$('.fczc-rc-but').click(function(){
		$('.tgPop-box').show();
		$('.tgClose').click(function(){
			$('.tgPop-box').hide();
			});
		});	
	});

$(document).ready(function(){
	$(window).scroll(function(){
		var thisTop=$(this).scrollTop();
		if(thisTop>250){
			$('.backTop,.map-zhaof,.fu-rx').show();
			}else{
				$('.backTop,.map-zhaof,.fu-rx').hide();
				}
		if(thisTop>200){
			$('.qy-box').show();
			}else{
				$('.qy-box').hide();
				}		
		});
	$('.fu-rx').hover(function(){
		$('.fw-rx-box').show();
		},function(){
			$('.fw-rx-box').hide();
			});	
	$('.backTop').hover(function(){
		$(this).children('.back').show();
		},function(){
			$(this).children('.back').hide();
			});	
	$('.back').click(function(){
		$('html,body').animate({scrollTop:0},500);
		});	
	});
