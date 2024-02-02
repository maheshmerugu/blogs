$(document).ready(function () {
    $(".what-we-do-slider").slick({
        slidesToShow: 2.5,
        dots: false,
        centerMode: false,
        variableWidth: false,
        infinite: false,
        // centerPadding:'90px',
        arrows: true,
        loop: false,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    auto: true,
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                },
            },
        ],
    });

    $(".latest_crousel").slick({
        slidesToShow: 1,
        dots: false,
        // rows: 1,
        // slidesToScroll: 6,
        infinite: false,
        arrows: true,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left "><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 1,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    auto: true,
                    slidesToShow: 1,
                },
            },
        ],
    });

    $(".wrapper_what_we_do").slick({
        slidesToShow: 2.5,
        dots: false,
        infinite: false,
        arrows: true,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                },
            },
        ],
    });
    $(".slider_text_testimonial").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: ".slider_image_testimonial",
        prevArrow:
            '<span class="left_arrow we-arrow-lefts why_prefer_left"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-rights why_prefer_right"><i class="fas fa-arrow-right"></i></span>',
    });
    $(".slider_image_testimonial").slick({
        slidesToShow: 5,
        centerPadding: "0px",
        centerMode: true,
        slidesToScroll: 1,
        // fade:true,
        cssEase: "linear",
        asNavFor: ".slider_text_testimonial",
        dots: false,
        arrows: false,
        // centerMode: true,
        focusOnSelect: true,
        loop: false,

        responsive: [
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });
    $(".our_acheiver_slider").slick({
        slidesToShow: 1.5,
        dots: false,
        rows: 1,
        infinite: false,
        arrows: true,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                },
            },
        ],
    });
    $(".wrapper_news_crousel").slick({
        slidesToShow: 3.25,
        dots: false,
        infinite: false,
        arrows: true,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                },
            },
        ],
    });
    $(".jee_testimonial_slider").slick({
        slidesToShow: 2.5,
        dots: false,
        rows: 1,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        speed: 300,
        centerPadding: "80px",
        // margin: "120px",
        prevArrow:
            '<span class="left_arrow we-arrow-lefting jee_testi"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-righting jee_testi"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    auto: true,
                    slidesToShow: 1,
                },
            },
        ],
    });
    $(".jee_testimonial_slider_course").slick({
        slidesToShow: 4,
        dots: false,
        rows: 1,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        speed: 300,
        prevArrow:
            '<span class="left_arrow we-arrow-left-jee_result"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right-jee_result"><i class="fas fa-arrow-right"></i></span>',

        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    auto: true,
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });
    $(".slider_text_testimonial_course_detail_page").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: ".slider_image_testimonial_course_detail_page",
        prevArrow:
            '<span class="left_arrow we-arrow-left-coursedetails"><i class="fas fa-arrow-left"></i></span>',
        nextArrow:
            '<span class="right-arrow we-arrow-right-coursedetails"><i class="fas fa-arrow-right"></i></span>',
    });
    $(".slider_image_testimonial_course_detail_page").slick({
        slidesToShow: 5,
        // centerPadding: '180px',
        centerMode: true,
        slidesToScroll: 1,
        // fade:true,
        cssEase: "linear",
        asNavFor: ".slider_text_testimonial_course_detail_page",
        dots: false,
        arrows: false,
        centerMode: true,
        focusOnSelect: true,

        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    // centerPadding: '100px',
                },
            },

            // {
            //     breakpoint: 767,
            //     settings: {
            //         // slidesToShow: 2,
            //         centerPadding: "15px",
            //     },
            // },
            {
                breakpoint: 575,
                settings: {
                    // auto: true,
                    slidesToShow: 1,
                },
            },
        ],
    });
    $("button.nav-link.logo_flex.is_open").click(function () {
        $(this).toggleClass("logout");
    });
    $("body").click(function () {
        $("button.nav-link.logo_flex.is_open").removeClass("logout");
    });
    $("button.nav-link.logo_flex.is_open").click(function (e) {
        e.stopPropagation();
    });
});

var mobileButton = document.querySelector(".mobile_button");
mobileButton.addEventListener("click", function (event) {
    document.body.classList.toggle("mobile_open");
});

var menuClose = document.querySelector(".header_menu .close_button");
menuClose.addEventListener("click", function () {
    document.body.classList.remove("mobile_open");
});

// $(".header_menu, .mobile_button").click(function (e) {
//     e.stopPropagation();
// });

$(".overlay_header").click(function () {
    $("body").removeClass("mobile_open");
});

// Opt Validation

let digitValidate = function (ele) {
    console.log(ele.value);
    ele.value = ele.value.replace(/[^0-9]/g, "");
};

let tabChange = function (val) {
    let ele = document.querySelectorAll("input.otp-input");
    if (ele[val - 1].value != "") {
        ele[val].focus();
    } else if (ele[val - 1].value == "") {
        ele[val - 2].focus();
    }
};

// input number validation

$(".header_menu ul").each(function () {
    $(this).addClass("level-" + ($(this).parents("ul").length + 1));
});
$(".header_menu ul li").addClass("menu-item");

$(".header_menu li").has("ul").addClass("has-menu");
$(".header_menu li.has-menu").append(
    "<i class='fa-solid fa-caret-down arrow_courses'></i>"
);

//course_neet navs

$(".course_description_btn").click(function () {
    document
        .getElementById("course_description_div")
        .scrollIntoView({ behavior: "smooth" });
});
$(".planner_btn").click(function () {
    document
        .getElementById("planner_div")
        .scrollIntoView({ behavior: "smooth" });
});
$(".fee_structure_btn").click(function () {
    document
        .getElementById("fee_structure_div")
        .scrollIntoView({ behavior: "smooth" });
});
$(".testimonials_btn").click(function () {
    document
        .getElementById("testimonials_div")
        .scrollIntoView({ behavior: "smooth" });
});
$(".methodologies_btn").click(function () {
    document
        .getElementById("methodologies_div")
        .scrollIntoView({ behavior: "smooth" });
});

/////////////////////////////////////////////////////

$(".course_description_btn1").click(function () {
    document
        .getElementById("course_description_div1")
        .scrollIntoView({ behavior: "smooth" });
});
$(".planner_btn1").click(function () {
    document
        .getElementById("planner_div1")
        .scrollIntoView({ behavior: "smooth" });
});
$(".fee_structure_btn1").click(function () {
    document
        .getElementById("fee_structure_div1")
        .scrollIntoView({ behavior: "smooth" });
});
$(".testimonials_btn1").click(function () {
    document
        .getElementById("testimonials_div1")
        .scrollIntoView({ behavior: "smooth" });
});
$(".methodologies_btn1").click(function () {
    document
        .getElementById("methodologies_div1")
        .scrollIntoView({ behavior: "smooth" });
});
/////////////////////////////////////////////////////

$(".course_description_btn2").click(function () {
    document
        .getElementById("course_description_div2")
        .scrollIntoView({ behavior: "smooth" });
});
$(".planner_btn2").click(function () {
    document
        .getElementById("planner_div2")
        .scrollIntoView({ behavior: "smooth" });
});
$(".fee_structure_btn2").click(function () {
    document
        .getElementById("fee_structure_div2")
        .scrollIntoView({ behavior: "smooth" });
});
$(".testimonials_btn2").click(function () {
    document
        .getElementById("testimonials_div2")
        .scrollIntoView({ behavior: "smooth" });
});
$(".methodologies_btn2").click(function () {
    document
        .getElementById("methodologies_div2")
        .scrollIntoView({ behavior: "smooth" });
});

// 18 july
// var x = window.matchMedia("(max-width: 991px)");
// if (x.matches) {
//     //  alert("dsdsc");
//     $("#header_courses").click(function () {
//         $(".level-2").toggle();
//     });
// }

// scholarship slider  25 July
$(".scholarship_testimonial_slider").slick({
    slidesToShow: 1,
    dots: false,
    rows: 1,
    slidesToScroll: 1,
    infinite: true,
    arrows: true,
    speed: 300,
    prevArrow:
        '<span class="left_arrow we-arrow-lefting testimonial_slider_arrow_left"><i class="fas fa-arrow-left"></i></span>',
    nextArrow:
        '<span class="right-arrow we-arrow-righting testimonial_slider_arrow_right"><i class="fas fa-arrow-right"></i></span>',

    responsive: [
        {
            breakpoint: 769,
            settings: {
                slidesToShow: 1,
            },
        },
        {
            breakpoint: 480,
            settings: {
                auto: true,
                slidesToShow: 1,
            },
        },
    ],
});
