
var App = function(){
	//获取不同设备的body高度
	var setDeviceHeight = function(){
		//console.log(window.screen.availHeight);
		//var screenH = window.screen.height;//677
		var winH = $(window).height();
		//console.log(winH);
		if($("body").attr("data-flag")){
			$("body").css({height:winH});
		}else{
			//
			return ;
		}
	}
	
	
	//设置通讯录展开
	var setListToggle = function(id){
		$(id + " dt").on("click",function(){		
			var display = $(this).next("dd").css("display");
			var len = $(this).next("dd").find("a").size();
			if(len == 0) return;
			if(display=="none"){
				$(id).find("dd").hide();
				$(id).find("dt").removeClass("active");
				$(this).addClass("active");
				$(this).next("dd").slideDown(100);
			}else{				
				$(id).find("dt").removeClass("active");
				$(this).removeClass("active");
				$(this).next("dd").slideUp(100);
			}
		})
	}
	
	
	return{
		init:function(){
			setDeviceHeight();
		},
		handleListToggle:function(id){
			setListToggle(id);
		}
	}
}();

//全局调用
$(function(){
	App.init();
})
