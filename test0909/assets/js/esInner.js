jQuery(function($) {

    var ua = navigator.userAgent.toLowerCase();

    // windowsのみ適用 macやiphone、ipadも適用させない
    var isWindows = (ua.indexOf('windows') > -1) && !((ua.indexOf('mac') > -1) && (ua.indexOf('os') > -1)) && !((ua.indexOf('iphone') > -1) || (ua.indexOf('ipad') > -1));

    if(isWindows) {
    $("html").easeScroll({
      frameRate: 60,
      animationTime: 1000,
      stepSize: 120,
      pulseAlgorithm: 1,
      pulseScale: 8,
      pulseNormalize: 1,
      accelerationDelta: 20,
      accelerationMax: 1,
      keyboardSupport: true,
      arrowScroll: 50,
      touchpadSupport: true,
      fixedBackground: true
      });}

    });

