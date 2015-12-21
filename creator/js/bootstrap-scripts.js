jQuery(document).ready(function(){
	$('#navbar').scrollspy();
	
	window.prettyPrint && prettyPrint();

	// fix sub nav on scroll
    var $win = $(window)
      , $nav = $('.navbar')
      , navTop = $('.navbar').length && $('.navbar').offset().top - 40
      , isFixed = 0

    processScroll()

    // hack sad times - holdover until rewrite for 2.1
    $nav.on('click', function () {
      if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47) }, 10)
    })

    $win.on('scroll', processScroll)

    function processScroll() {
      var i, scrollTop = $win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        $nav.addClass('navbar-fixed-top').find('.brand').show()
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        $nav.removeClass('navbar-fixed-top').find('.brand').hide()
      }
    }
    
    $nav.find('.brand').on('click', function() {
    	jQuery('html, body').animate({scrollTop : 0}, 500);
    	return false;
    });
    
    $('.nav-tabs a').click(function (e) {
    	e.preventDefault();
    	$(this).tab('show');
    })
});