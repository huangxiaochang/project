// JavaScript Document
function amendTab(amendTab,album,dd,amendTabDef,timer){
	var amendDd = document.getElementById(amendTab).getElementsByTagName(dd);
	var amendDdLen = amendDd.length;		      						
	for ( i=0; i<amendDdLen; i++){
		twoTab(amendDd[i],i)
	}      						
	function twoTab(list , num){
		list.onmouseover = function(){out = setTimeout(function(){tabs(num)},timer );}
		list.onmouseout=function(){ clearTimeout(out)};
	}
	function tabs(num){
    	for(i=0; i<amendDdLen; i++){
      		var amendDds = amendDd[i];
      		var j = i + 1;     							
      		if( i == num){
      			amendDds.className=amendTabDef;
      				document.getElementById(album+j).style.display = 'block'
      		}
			else{
      			amendDds.className='';
      			document.getElementById(album+j).style.display ='none'
      		}
		}
	}	
}
