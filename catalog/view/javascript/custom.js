$( document ).ready(function(){	
	$(".left_nav li a").on("click",function(){
		$(this).siblings(".sub_left_nav").slideToggle();
	});
	
	$(".nav_toggle").on("click",function(){
		$(".main_nav").slideToggle();
	});
	
});
