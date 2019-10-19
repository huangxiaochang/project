$(function(){
		var url ='';
		var type = "house";
		var  $sear_list =$('.sous-ts');
		var  $i_search =$('#i_search');
		$('.keyword').data('url',obj[type].autoUrl);
		$('.search-tr a').click(function(){
			$(this).addClass('hover').siblings().removeClass('hover');
			if($(this).text() =='新房'){
				$('.keyword').val('请输入楼盘名称(中文/拼音/简拼)、地址');
				type = "house";
				$('.keyword').data("url",obj[type].autoUrl);
			}
			else if($(this).text() =='二手房'){
				$('.keyword').val('请输入小区名称/地址/标题');
				type = "sale";
				$('.keyword').data("url",obj[type].autoUrl);
			}
			else if($(this).text() =='租房'){
				$('.keyword').val('请输入小区名称/地址/标题');
				type = "rent";
				$('.keyword').data("url",obj[type].autoUrl);
			}
			else{
				$('.keyword').val('请输入搜索内容');
				type = "news";
				$('.keyword').data("url",obj[type].autoUrl);
			}
		});
		
		 $i_search.on("submit",function() {
					var keyword = $(".keyword").val();
					if(keyword == obj[type].msg){
						$(this).attr("action", obj[type].initUrl);
					}else{
						$(this).attr("action", obj[type].url + keyword + obj[type].hz);
					}
					if($(this).attr('action') ==""){
						return false;
					}
				}).find(".keyword").on("keydown",function(e){
						$sear_list.show();
						switch (e.which) {
						case 9:
							$sear_list.html('');
							$sear_list.hide();
							break;
						case 13:
							
							var hid = $sear_list.find('.hover').attr('hid');
							if(hid !=undefined){
								$(this).val($sear_list.find('.hover').find('span').text());
								 window.open('/house/'+hid);
								  return false; 
							}else{
								$i_search.submit();
							}
							// $t.val($pop.hide().find(".pop b").text());
							break;
						case 38:
							var $p = $sear_list.find(".hover").removeClass("hover");
							if ($p.index() > 0) $p = $p.prev().addClass("hover");
							else $p = $sear_list.find("li").last().addClass("hover");
							$(this).val($p.find("span").text());
							return false;
						case 40:
							var $p = $sear_list.find(".hover").removeClass("hover");
							if ($p.index() < l) $p = $p.next().addClass("hover");
							else $p = $sear_list.find("li").first().addClass("hover");
							$(this).val($p.find("span").text());
							return false;
					}
				}).on('keyup',function(e){
					switch (e.which) {
						case 9:
						case 38:
						case 40:
							return false;
							break;
						default:
							var val = $(this).val(),
								str = "<li class='hover' style='display:none'><span>" + val + "</span></li>";
							if (val == "" || e.which == 13){
								$sear_list.hide();
								// $(this).val($sear_list.find('.hover span').text());
							}else {
								var url = $(this).data("url");
								if(url ='undefined') url =obj[type].autoUrl;
									$.ajax({
										url: url,
										dataType: 'json',
										data: {
											key: val
										}
									}).done(function(data) {
										if (data.length > 0) {
											var i = 0,
												html = str,
											l = data.length;
											for (; i < l; i++) {
												html += '<li hid="'+data[i].hid+'"><span>' + data[i].name + '</span> ' + data[i].address + '</li>';
											}
											$sear_list.html(html).show();
											// resize();
										} else {
											$sear_list.html('');
											$sear_list.hide();
										}
									});
								// }, 400)
							}
							// $sear_list.html(str);
					}
				}).on("focus",function(){
					if($(this).val() == obj[type].msg){
						$(this).val("");
					}
				}).on("blur",function(){
					if($(this).val() == ""){
						$(this).val(obj[type].msg);
					}
				}).data("url",obj[type].autoUrl);
			
			$('.sous-ts').on('click','li',function(){
				window.open('/house/'+$(this).attr('hid'));
			})
				$("body").on("click",function(){
					$sear_list.slideUp(100);
				})

});