/* ------------------------------
    SMOOTH SCROLL
  ------------------------------ */
jQuery(function($){
  $('a[href^="#"]').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top - 90;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});

/* ------------------------------
    SCROLL ANIMATION
  ------------------------------ */
  jQuery(window).scroll(function(){
    var windowHeight = jQuery(window).height(),
    topWindow = jQuery(window).scrollTop();

    //ナビゲーションフィックス
    if(!navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/)){
    $('#works__slider').each(function(){
      var targetPosition = $(this).offset().top;
      var targetPosition02 = $('#check__point').offset().top;
      var targetPosition03 = $('#section__compare').offset().top;
        if(topWindow > targetPosition - 200){
          if(topWindow > targetPosition02 - 300) {
            $('#fixed__nav .title__item.num1').removeClass('is__active');
            $('#fixed__nav li.num1').removeClass('is__active');
            $('#fixed__nav .title__item.num2').addClass('is__active');
            $('#fixed__nav li.num2').addClass('is__active');
          }else{
            $('#fixed__nav .title__item.num1').addClass('is__active');
            $('#fixed__nav li.num1').addClass('is__active');
            $('#fixed__nav .title__item.num2').removeClass('is__active');
            $('#fixed__nav li.num2').removeClass('is__active');
          }
          if(topWindow > targetPosition03 - 500) {
            $('#fixed__nav').css({
              'position':'absolute',
              'top':'auto',
              'bottom':'-50px'
            });
          }else{
            $('#fixed__nav').css({
              'position':'fixed',
              'top':'200px',
              'bottom':'auto'
            });
          }
        }else{
          $('#fixed__nav').css({
            'position':'absolute',
            'top':'auto',
            'bottom':'auto'
          });
        }
    });}

    //フェイドアニメーション
    $('.animation01').each(function(){
      var targetPosition = $(this).offset().top;
      scalePoint = 1 + (topWindow - targetPosition) * -0.08;
        if(topWindow > targetPosition - windowHeight){
          $(this).addClass('fadeInUp01');
        }else{
          $(this).removeClass('fadeInUp01');
        }
    });
    $('.animation02').each(function(){
      var targetPosition = $(this).offset().top;
      scalePoint = 1 + (topWindow - targetPosition) * -0.08;
        if(topWindow > targetPosition - windowHeight){
          $(this).addClass('fadeInUp03');
        }else{
          $(this).removeClass('fadeInUp03');
        }
    });
    $('.animation03').each(function(){
      var targetPosition = $(this).offset().top;
      scalePoint = 1 + (topWindow - targetPosition) * -0.08;
        if(topWindow > targetPosition - windowHeight){
          $(this).addClass('fadeInUp05');
        }else{
          $(this).removeClass('fadeInUp05');
        }
    });

  });


/* ------------------------------
    SLIDER SETTINGS
  ------------------------------ */
  $(function() {$('.carousel__items').slick({
    dots: true,
    draggable: true,
    accessibility: true,
    arrows: true,
    infinite: true,
    pauseOnHover: false,
    pauseOnFocus: false,
    centerMode: true,
    centerPadding: '0px',
    focusOnSelect: true,
    initialSlide: 0,
    slidesToShow: 3
  });})
  $(function() {
	$('.directer-box').slick({
	dots: true,
	arrows: true,
	infinite: true,
	centerPadding: '60px',	
	slidesToShow: 4,
	slidesToScroll: 1,
	responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]  
	});
});
  $(function() {
	$('.reason-box').slick({
	dots: true,
	arrows: true,
	infinite: true,
	centerMode: true,
	centerPadding: '0',
	slidesToShow: 1,
	slidesToScroll: 1
	});
});

  if(navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/)){
    $(function() {$('.carousel__noinfinite').slick({
      dots: false,
      draggable: true,
      accessibility: true,
      arrows: true,
      infinite: true,
      pauseOnHover: false,
      pauseOnFocus: false,
      centerMode: true,
      centerPadding: '0px',
      focusOnSelect: true,
      initialSlide: 0,
      slidesToShow: 3
    });})
  }else{
    $(function() {$('.carousel__noinfinite').slick({
      dots: false,
      draggable: true,
      accessibility: true,
      arrows: true,
      infinite: true,
      pauseOnHover: false,
      pauseOnFocus: false,
      centerMode: true,
      centerPadding: '0px',
      focusOnSelect: true,
      initialSlide: 1,
      slidesToShow: 3
    });})
  }

/* ------------------------------
    TOGGLE CONTENS
  ------------------------------ */
$(function() {
  $('#faq__content dl').on('click', function() {
      $(this).toggleClass('is__open');
  });
});

function viewMenu() {
  jQuery(function($){
    if($('#item__switch .transform__btn').hasClass('move') || $('#item__switch .transform__btn').hasClass('move')){
      $('html').removeClass('locked');
      $('#item__switch .transform__btn').removeClass('move');
      $('#hidden__nav').removeClass('view');
      $('#logo').removeClass('light');
      setTimeout(function(){
        $('#item__switch .transform__btn').removeClass('active');
        $('.floating__item__section').removeClass('hidden');
      }, 700);
    }else{
      $('html').addClass('locked');
      $('#item__switch .transform__btn').addClass('active');
      $('.floating__item__section').addClass('hidden');
      $('#logo').addClass('light');
      setTimeout(function(){
        $('#item__switch .transform__btn').addClass('move');
        $('#hidden__nav').addClass('view');
      }, 700);
    }
  });
}

function changeTab(num) {
  num = num;
  jQuery(function($){
    $('.title__item').removeClass('is__active');
    $('.carousel__noinfinite').removeClass('is__active');
    $('.title__item.num'+num).addClass('is__active');
    $('.carousel__noinfinite.num'+num).addClass('is__active');
  });
}