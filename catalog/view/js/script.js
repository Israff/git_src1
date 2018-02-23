$(function() {
    $('.main_banner').owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        dots: true,
        navText: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true
    });

    $('.products_nav a[href^="#"]').click(function(event) {
        event.preventDefault();

        $('html, body').animate({
            scrollTop: ($($.attr(this, 'href')).offset().top - $('header').innerHeight())
        }, 500);
    });

    $('.product_popup_icon').click(function(event) {
        event.stopPropagation();
        $(this).next('.product_popup').toggleClass('active');
    });

    $('.product_popup_content').click(function(event) {
        event.stopPropagation();
    });

    $(document).click(function() {
        $('.product_popup.active').removeClass('active');
        $('.main_nav ul.active').removeClass('active');
        $('.overlay.active').removeClass('active');
        $('.products_nav ul.active').next().slideUp();
        $('.products_nav_toggle.active').removeClass('active');
    });

    $('.main_nav_toggle').click(function() {
        $(this).next().toggleClass('active');
        $('.overlay').toggleClass('active');
    });

    $('.products_nav_toggle').click(function(event) {
        event.stopPropagation();
        $(this).toggleClass('active');
        $(this).next().slideToggle(function() {
            if ($(this).is(':visible')) {
                $(this).css('display', 'flex');
            } else {
                $(this).removeAttr('style');
            }
        });
    });

    $('.main_nav').click(function(event) {
        event.stopPropagation();
    });

    setBodyPadding();
    $(window).resize(function() {
        setBodyPadding();
    });

    function setBodyPadding() {
        $('body').css('padding-top', $('header').innerHeight());
    }

    $('.minus').click(function() {
        var input = $(this).parent().find('input');
        var count = parseInt(input.val()) - 1;
        count = count < 1 ? 1 : count;
        input.val(count);
        input.change();
        return false;
    });
    $('.plus').click(function() {
        var input = $(this).parent().find('input');
        input.val(parseInt(input.val()) + 1);
        input.change();
        return false;
    });

    $('.delivery input[name=delivery]').change(function() {
        var index = Math.floor($(this).index() / 2);
        $('.delivery-ways fieldset:visible').slideUp();
        $('.delivery-ways fieldset').eq(index).slideDown();
    });

    $('select').niceSelect();

    $('.basket_form').submit(function(event) {
        event.preventDefault();
        $('.modal_order').fadeIn();
        $('html, body').animate({
            scrollTop: $('.modal_order').offset().top
        }, 500);
        $('.overlay').fadeIn();
    });

    $('.vacancies_respond_btn').click(function(event) {
        event.preventDefault();
        $('.modal_vacancies').fadeIn();
        $('html, body').animate({
            scrollTop: $('.modal_vacancies').offset().top
        }, 500);
        $('.overlay').fadeIn();
    });

    $('.popup_form').submit(function(event) {
        event.preventDefault();

        var form = $(this);

        form.find('input:required').each(function() {
            var input = $(this);
            switch (input.prop('type')) {
                case 'checkbox':
                    if (!input.is(':checked')) {
                        input.addClass('invalid');
                    } else {
                        input.removeClass('invalid');
                    }
                    break;
                default:
                    if (!input.val()) {
                        input.addClass('invalid');
                    } else {
                        input.removeClass('invalid');
                    }
                    break;
            }
        });

        if (form.find('input.invalid').length) {
            form.find('.tip').slideDown();
            $('html, body').animate({
                scrollTop: form.find('input.invalid').eq(0).parents('fieldset').offset().top
            }, 500);
        } else {
            form.find('.tip').slideUp();
            form.parents('.modal_content > div[class^=modal_]').fadeOut();
            $('.modal_order_success').fadeIn();
            $('.basket_form button[type=submit]').attr('disabled', true);
        }
    });

    $('.overlay').click(function() {
        $('.modal_content > div[class^=modal_]:visible').fadeOut();
        $(this).fadeOut();
    });

    $('.modal_content > div[class^=modal_] .close').click(function() {
        event.preventDefault();
        $(this).parents('.modal_content > div[class^=modal_]').fadeOut();
        $('.overlay').fadeOut();
    });


    $("form.product_form > fieldset input[type='radio']").change( function()
    {
        var price = $(this).attr("data-price"), special = $(this).attr("data-special");

        if( special !== undefined )
        {
            $(this).parent().next().find("span.price").html( special );
            $(this).parent().next().find("span.price_discount").html( price );
        }else{
            $(this).parent().next().find("span.price").html( price );
        }
    });

});
