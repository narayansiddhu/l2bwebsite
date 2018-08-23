jQuery('#venture').click(function($) {
    jQuery('#venture_img').css('display', 'block');
    jQuery('#grow_img').css('display','none');
    jQuery('#expand_img').css('display','none');
    jQuery('#launch_img').css('display','none');
    jQuery('#venture').addClass('active');
    jQuery('#grow').removeClass('active');
    jQuery('#expand').removeClass('active');
    jQuery('#launch').removeClass('active');
});

jQuery('#grow').click(function($) {
    jQuery('#grow_img').css('display','block');
    jQuery('#venture_img').css('display', 'none');
    jQuery('#expand_img').css('display','none');
    jQuery('#launch_img').css('display','none');
    jQuery('#grow').addClass('active');
    jQuery('#venture').removeClass('active');
    jQuery('#expand').removeClass('active');
    jQuery('#launch').removeClass('active');
});
jQuery('#expand').click(function($) {
    jQuery('#expand_img').css('display','block');
    jQuery('#venture_img').css('display', 'none');
    jQuery('#grow_img').css('display','none');
    jQuery('#launch_img').css('display','none');
    jQuery('#expand').addClass('active');
    jQuery('#venture').removeClass('active');
    jQuery('#grow').removeClass('active');
    jQuery('#launch').removeClass('active');
});
jQuery('#launch').click(function($) {
    jQuery('#launch_img').css('display','block');
    jQuery('#venture_img').css('display', 'none');
    jQuery('#grow_img').css('display','none');
    jQuery('#expand_img').css('display','none');
    jQuery('#launch').addClass('active');
    jQuery('#venture').removeClass('active');
    jQuery('#grow').removeClass('active');
    jQuery('#expand').removeClass('active');
});