/**
* author : ahuing
* date   : 2015-04-10
* name   : jqDuang v1.15
* modify : 2015-9-14 18:01:17
 */
!function(e){function t(t){return"index"==t?this.data("jqDuang").index:this.each(function(){var i=e(this),a=i.data("jqDuang"),n="object"==typeof t&&t;a||(i.data("jqDuang",a=new s(this,n)),a.init()),"string"==typeof t?a[t]():"number"==typeof t&&a.play(a.loopNext=t)})}var s=function(t,i){this.o=e.extend({},s.defaults,i),this.$self=e(t);var a=this.o.obj.split("|");this.$obj=this.$self.find(a[0]),this.$objExt=e(a[1]),this.$obj.length||(this.$obj=this.$objExt,this.$objExt=[]),this.dire=e.inArray(this.o.effect,["left","leftLoop","leftMarqueue"])<0&&"top"||"left",this.objAttr="top"==this.dire&&"height"||"width",this.index=this.o.index,this.effect=this.o.effect.replace(this.dire,""),this.L=this.$obj.length,this.pages=!this.effect&&Math.ceil((this.L-this.o.visible)/this.o.steps)+1||Math.ceil(this.L/this.o.steps),this.WH={width:this.$obj.outerWidth(!0),height:this.$obj.outerHeight(!0)}};s.defaults={obj:"li",cell:"",trigger:"mouseover",effect:"fade",speed:500,index:0,autoplay:1,interval:3e3,prevbtn:"",nextbtn:"",delay:150,easing:"swing",visible:1,steps:1,overstop:1,showtit:0,pagewrap:"",btnLoop:1,wheel:0,actclass:"act"},s.prototype={init:function(){function t(t){var a=s.$self.find(i[t+"btn"]);return!(1*i.btnLoop)&&"prev"==t&&a.addClass("disabled"),a["Marqueue"==s.effect?i.trigger:"click"](function(){e(this).hasClass("disabled")||(s.s="next"==t?-1:1,"Marqueue"==s.effect?s.next():s[t]())}).attr({unselectable:"on",onselectstart:"return false;"}),!0}var s=this,i=s.o,a=s.$obj,n=a.parent(),o=n.parent();if(!(s.pages<=1||s.L<=i.visible)){switch(!i.speed&&(s.effect="fade"),s.effect){case"fade":a.css({display:"none"}).eq(i.index).show();break;case"fold":a.css({position:"absolute",display:"none",left:0,top:0}).eq(i.index).show();var l=s.WH;l.position="relative",o.css(l);break;case"weibo":n.css("position","relative");var l={position:"relative",overflow:"hidden"};l[s.objAttr]=s.WH[this.objAttr]*i.visible,o.css(l);break;case"Marqueue":default:a.css({"float":"top"==s.dire?"none":"left"});var r={position:"relative",overflow:"hidden"};r[s.dire]=-s.WH[s.objAttr]*i.index,r[s.objAttr]=9999,s.effect&&(s.$obj=n.append(a.slice(0,i.visible).clone()).prepend(a.slice(a.length-i.visible).clone()).children(),r[s.dire]=-(i.visible+i.index*i.steps)*s.WH[s.objAttr],"Marqueue"==s.effect&&(s.s=-1,s.scrollSize=s.WH[s.objAttr]*s.L)),n.css(r);var l={overflow:"hidden",position:"relative"},f=a.eq(0),d=parseInt(f.css("margin-"+this.dire))-parseInt(f.css("margin-"+("left"==this.dire?"right":"bottom")));l[s.objAttr]=s.WH[s.objAttr]*i.visible+d,o.css(l)}var c;if(i.cell&&"Marqueue"!=s.effect){var p=i.cell.split("|");if(s.$cells=p.length>1?s.$self.find(p[1]):s.$self.find(p[0]).children(),s.$cells.length)s.$cells=s.$cells.slice(0,s.pages);else{for(var h="",u=0;u<s.pages;u++)h+="<i>"+(u+1)+"</i>";s.$cells=e(h).appendTo(s.$self.find(p[0]))}s.$cells[i.trigger](function(){return clearTimeout(c),s.loopNext=s.$cells.index(this),c=setTimeout(function(){s.play(s.loopNext)},i.delay),"click"==i.trigger?!1:void 0}).eq(s.index).addClass(i.actclass)}1*i.autoplay&&(s.start(),1*i.overstop&&s.$obj.add(s.$cells).add(i.prevbtn&&"Marqueue"!=s.effect&&i.prevbtn+","+i.nextbtn||null).on("mouseover",e.proxy(s.stop,s)).on("mouseout",e.proxy(s.start,s))),1*i.showtit&&1==i.visible&&o.after('<a class="tit-duang" target="_blank" href="'+a.eq(i.index).data("url")+'">'+a.eq(i.index).data("title")+"</a>"),i.pagewrap&&s.$self.find(i.pagewrap).html(1*s.index+1+"/"+s.pages),i.prevbtn&&t("prev")&&t("next"),1*i.wheel&&e.fn.mousewheel&&o.mousewheel(function(e,t){clearTimeout(c),c=setTimeout(function(){s[t>0?"prev":"next"]()},100)}),s.$objExt.length>i.index&&s.$objExt.css("display","none").eq(i.index).show()}},start:function(){clearInterval(this.t1),this.t1=setInterval(e.proxy(this.next,this),this.o.interval)},stop:function(){clearInterval(this.t1)},next:function(){this.play("Marqueue"!=this.effect&&(this.loopNext=this.index+1)%this.pages)},prev:function(){this.loopNext=this.index-1,this.play((this.loopNext+this.pages)%this.pages)},play:function(t){var s=this,i=s.o,a=s.$obj,n=a.parent(),o=s.loopNext;if(s.index!=t||"Marqueue"==s.effect){switch(s.$self.trigger("beforeFun"),s.effect){case"fade":a.eq(s.index).hide(),a.eq(t).animate({opacity:"show"},i.speed,i.easing);break;case"fold":a.stop(!0,!0).eq(s.index).animate({opacity:"hide"},i.speed,i.easing),a.eq(t).animate({opacity:"show"},i.speed,i.easing);break;case"Marqueue":n.css(s.dire,function(e,t){var i=parseInt(t)+s.s;return i<=-s.scrollSize?i=0:i>=0&&(i=-s.scrollSize),i});break;case"weibo":var l={};l[s.dire]=9*s.WH[s.objAttr]/8,n.stop(!0,!0).animate(l,i.speed,i.easing,function(){var t=n.children()[a.length-1];t.style.display="none",n[0].insertBefore(t,n[0].children[0]),n[0].style.top=0,e(t).fadeIn()});break;default:var r=0;if("Loop"==this.effect){if(n.is(":animated"))return!1;var f,d=s.L%i.steps;d&&o==s.pages?r=d-i.steps:d&&-1==o&&(r=i.steps-d),f=o*i.steps+i.visible+r;var c=function(){n.css(s.dire,-s.WH[s.objAttr]*(t*i.steps+i.visible))}}else(o==s.pages-1||-1==o)&&(r=s.L-t*i.steps-i.visible);var l={};l[s.dire]=-s.WH[s.objAttr]*("Loop"==this.effect?f:t*i.steps+r),n.stop(!0).animate(l,i.speed,i.easing,c)}if(1*i.showtit&&1==i.visible){var p=a.eq(t+("Loop"==this.effect?1:0)).data();s.$self.find(".tit-duang").html(p.title)[0].href=p.url}i.pagewrap&&s.$self.find(i.pagewrap).html(t+1+"/"+s.pages),i.cell&&s.$cells.removeClass(i.actclass).eq(t).addClass(i.actclass),s.$objExt.length>t&&s.$objExt.css("display","none").eq(t).show(),s.index=t,1*i.btnLoop||!i.prevbtn||(s.$self.find(i.prevbtn+","+i.nextbtn).removeClass("disabled"),0==t&&s.$self.find(i.prevbtn).addClass("disabled"),t==s.pages-1&&s.$self.find(i.nextbtn).addClass("disabled")),s.$self.trigger("afterFun",[t])}}};var i=e.fn.jqDuang;e.fn.jqDuang=t,e.fn.jqDuang.Constructor=s,e.fn.jqDuang.noConflict=function(){return e.fn.jqDuang=i,this},e(window).on("load",function(){e(".jqDuang").each(function(){var s=e(this);t.call(s,s.data())})})}(jQuery);