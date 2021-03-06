function ajaxError(data) {
    var response = JSON.parse(data.responseText);
    if (response.message !== undefined) {
        var header = (response.msgHeader != undefined) ? response.msgHeader : null;
        toastr.error(response.message, header);
    }
    console.log(data);
}

// Full height of sidebar
function fix_height() {
    var heightWithoutNavbar = $("body > #wrapper").height() - 61;
    $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

    var navbarHeigh = $('nav.navbar-default').height();
    var wrapperHeigh = $('#page-wrapper').height();

    if(navbarHeigh > wrapperHeigh){
        $('#page-wrapper').css("min-height", navbarHeigh + "px");
    }

    if(navbarHeigh < wrapperHeigh){
        $('#page-wrapper').css("min-height", $(window).height()  + "px");
    }
}

// Bind clickable elements
function documentBind(){
    // Collapse ibox function
    $('.collapse-link').unbind('click').click( function() {
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        var content = ibox.find('div.ibox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        ibox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function () {
            ibox.resize();
            ibox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close ibox function
    $('.close-link').unbind('click').click( function() {
        var content = $(this).closest('div.ibox');
        //content.remove();
        content.slideUp(400);
    });

    // Open close right sidebar
    $('.right-sidebar-toggle').unbind('click').click(function(){
        $('#right-sidebar').toggleClass('sidebar-open');
    });

    // Initialize slimscroll for right sidebar
    $('.sidebar-container').slimScroll({
        height: '100%',
        railOpacity: 0.4,
        wheelStep: 10
    });

    // Open close small chat
    $('.open-small-chat').unbind('click').click(function(){
        $(this).children().toggleClass('fa-comments').toggleClass('fa-remove');
        $('.small-chat-box').toggleClass('active');
    });

    // Initialize slimscroll for small chat
    $('.small-chat-box .content').slimScroll({
        height: '234px',
        railOpacity: 0.4
    });

    // Small todo handler
    $('.check-link').unbind('click').click( function(){
        var button = $(this).find('i');
        var label = $(this).next('span');
        button.toggleClass('fa-check-square').toggleClass('fa-square-o');
        label.toggleClass('todo-completed');
        return false;
    });

    // Add slimscroll to element
    $('.full-height-scroll').slimscroll({
        height: '100%'
    });

    $('.panel .panel-heading a.close-link').click(function (e) {
        e.preventDefault();

        $(this).parents('.panel').slideUp('slow', function () {
            $(this).remove();
        });
    });

    // On click buttons with ajax actions
    $('.ajaxLoad').unbind('click').click( function(e){
        e.preventDefault();

        if ($(this).parents('form').length == 1) {
            var $form = $(this).parents('form');

            var values = $form.serializeArray();
            values = values.concat(
                $form.find('input[type=checkbox]:not(:checked)').map(
                    function() {
                        return {"name": this.name, "value": 0}
                    }).get()
            );

            jQuery.ajax({
                url: $form.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'editSettings',
                    data: values
                },
                success: function (data) {
                    var type = 'error';
                    if (data.error) {
                        console.log(data.message);
                    } else {
                        type = 'success';
                    }
                    var header = (data.msgHeader != undefined) ? data.msgHeader : null;

                    toastr[type](data.message, header);
                },
                error: function (data) {
                    ajaxError(data);
                }
            });
        }
    });
}

$(document).ready(function () {
    // Set toastr options
    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        showDuration: "fast",
        hideDuration: "fast",
        timeOut: 4000,
        extendedTimeOut: "4000"
    };
    
    if (toastrDataArr.length > 0){
        $.each(toastrDataArr, function(key, val) {
            toastr[val.type](val.msg, val.header);
        });
    }

    // iCheck init
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });

    // Pace start on ajax
    $(document).ajaxStart(function() {
        Pace.restart();
    });

    // Add body-small class if window less than 768px
    if ($(this).width() < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }

    // MetsiMenu
    $('#side-menu').metisMenu();

    // Bind clickable elements
    documentBind();

    // Close menu in canvas mode
    $('.close-canvas-menu').unbind('click').click( function() {
        $("#wrapper").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    // Minimalize menu
    $('.navbar-minimalize').click(function (e) {
        e.preventDefault();

        $("body").toggleClass("mini-navbar", 'slow');
        var value = $('body').hasClass('mini-navbar') ? 'mini-navbar' : 'null';
        document.cookie = 'menuPosition=' + value + '; path=/admin;';
        SmoothlyMenu();
    });

    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Move modal to body
    // Fix Bootstrap backdrop issu with animation.css
    $('.modal').appendTo("body");

    // Full height of sidebar
    fix_height();

    // Fixed Sidebar
    $(window).bind("load", function () {
        if ($("body").hasClass('fixed-sidebar')) {
            $('.sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });
        }
    });

    // Move right sidebar top after scroll
    $(window).scroll(function(){
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav') ) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });

    $(document).bind("load resize scroll", function() {
        if(!$("body").hasClass('body-small')) {
            fix_height();
        }
    });

    $("[data-toggle=popover]").popover();
});


// Minimalize menu when screen is less than 768px
$(window).bind("resize", function () {
    fix_height();
    if ($(this).width() < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }
});

// Local Storage functions
// Set proper body class and plugins based on user configuration
$(document).ready(function() {
    if (localStorageSupport) {

        var collapse = localStorage.getItem("collapse_menu");
        var fixedsidebar = localStorage.getItem("fixedsidebar");
        var fixednavbar = localStorage.getItem("fixednavbar");
        var boxedlayout = localStorage.getItem("boxedlayout");
        var fixedfooter = localStorage.getItem("fixedfooter");

        var body = $('body');

        if (fixedsidebar == 'on') {
            body.addClass('fixed-sidebar');
            $('.sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });
        }

        if (collapse == 'on') {
            if(body.hasClass('fixed-sidebar')) {
                if(!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }
            } else {
                if(!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }

            }
        }

        if (fixednavbar == 'on') {
            $(".navbar-static-top").removeClass('navbar-static-top').addClass('navbar-fixed-top');
            body.addClass('fixed-nav');
        }

        if (boxedlayout == 'on') {
            body.addClass('boxed-layout');
        }

        if (fixedfooter == 'on') {
            $(".footer").addClass('fixed');
        }
    }
});

// check if browser support HTML5 local storage
function localStorageSupport() {
    return (('localStorage' in window) && window['localStorage'] !== null)
}

// For demo purpose - animation css script
function animationHover(element, animation){
    element = $(element);
    element.hover(
        function() {
            element.addClass('animated ' + animation);
        },
        function(){
            //wait for animation to finish before removing classes
            window.setTimeout( function(){
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 100);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 300);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}

// Dragable panels
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable(
        {
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8
        })
        .disableSelection();
}