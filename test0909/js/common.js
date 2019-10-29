// スムーズスクロール
$(function(){
   $('a[href^=#]').click(function() {
      var speed = 800;
      var href= $(this).attr("href");
      var target = $(href == "#" || href == "" ? 'html' : href);
      var position = target.offset().top;
      $('body,html').animate({scrollTop:position}, speed, 'swing');
      return false;
   });
});


// fixed nav（スクロール後 表示ヘッダー）
$(function () {
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 200) {
      $(".cb-header").slideDown();
    } else {
      $(".cb-header").slideUp();
    }
  });
});


// CSS3 アニメーション挙動
$('.animation').css('visibility','hidden');
$(window).scroll(function(){
 var windowHeight = $(window).height(),
     topWindow = $(window).scrollTop();
 $('.animation').each(function(){
  var targetPosition = $(this).offset().top;
  if(topWindow > targetPosition - windowHeight + 100){
   $(this).addClass("fadeInDown delay").css('visibility','visible');
  }
 });
});


// SP-MENU
$(function() {
  $("#panel-btn").click(function() {
    $("#panel").slideToggle(400);
    $("#panel-btn-icon").toggleClass("close");
    return false;
  });
});
$('.nav a').on('click', function(){
	if (window.innerWidth <= 768) {
		$('#panel-btn').click();
	}
});

















