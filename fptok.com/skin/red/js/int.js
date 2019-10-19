$(function() {
	
    $('.search .selecter').hover(function () {
        $(this).find("ul").show();
    }, function () {
        $(this).find("ul").hide();
    });
    $(".change-city").hover(function () {
        $(this).find("dt").addClass("hover");
        $(this).find("dl").show();
    }, function () {
        $(this).find("dt").removeClass("hover");
        $(this).find("dl").hide();
    });

    $('.search .selecter li').click(function () {
        $('#type').val($(this).attr('data-value'));
        $(this).parent().parent().find('strong').html($(this).html());
        $(this).parent().parent().find("ul").slideUp(200);
    });

    $('.main-nav li,.top-nav li').hover(function () {
        $(this).find("dl").show();
        $(this).find("a:first").addClass("hover");
    }, function () {
        $(this).find("dl").hide();
        $(this).find("a:first").removeClass("hover");
    });

    if ($(".menu-hide").length > 0) {
        $(".all-real").hover(function () {
            $(this).find(".menu-hide").slideDown(300);
        }, function () {
            $(this).find(".menu-hide").slideUp(300);
        });
    }

    $("a.tabs").hover(function () {
        var gclass = $(this).attr('data-group'), rel = $(this).attr('data-rel');
        $(this).parent().find("a.current").removeClass("current");
        $(this).addClass("current");
        $('.'+gclass).hide();
        $("#" + rel).show();
        $("#" + rel).find("img.lazy").trigger("appear");
        return false;
    });
    $(".all-real .item,.index-bussiness-search .item").hover(function () {
        if ($(this).find(".more").length > 0) {
            $(this).addClass("hover");
        }
    }, function () {
        $(this).removeClass("hover");
    });
  
   

    if ($(".index-news-change").length > 0) {
        $(".index-news-change .s1").click(function () {
            if ($("#index-news-list1").is(":hidden")) {
                $(this).addClass("current");
                $(".index-news-change .s2").removeClass("current");
                $("#index-news-list1").show();
                $("#index-news-list2").show();
                $("#index-news-list3").hide();
                $("#index-news-list4").hide();
            }
            ;
        });
        $(".index-news-change .s2").click(function () {
            if ($("#index-news-list3").is(":hidden")) {
                $(".index-news-change .s1").removeClass("current");
                $(this).addClass("current");
                $("#index-news-list1").hide();
                $("#index-news-list2").hide();
                $("#index-news-list3").show();
                $("#index-news-list4").show();
            }
            ;
        });
        $(".index-news-change .prev,.index-news-change .next,.index-news-change .btn").click(function () {
            if ($(".index-news-change .s1").hasClass("current")) {
                $(".index-news-change .s2").trigger("click");
            } else {
                $(".index-news-change .s1").trigger("click");
            }
        });
    }

    $(".back-top").click(function () {
        $('html,body').animate({scrollTop: '0px'}, 400);
        return false;
    });

    $(".select-box").hover(function () {
        $(this).find("ul").show();
    }, function () {
        $(this).find("ul").hide();
    });
	
});

var tur = true;
function haha() {
    t = $(document).scrollTop();
    if (t > 300) {
        $("#float-links").show();
        setTimeout(function () {
            if ($(window).width() > 1280) {
                $("#float-links").addClass("hover");
            }
        }, 100);
    } else {
        $("#float-links").removeClass("hover");
    }
    tur = true;
}

window.onscroll = function () {
    if (tur) {
        setTimeout(haha, 500);
        tur = false;
    } else {
        
    }
} 

