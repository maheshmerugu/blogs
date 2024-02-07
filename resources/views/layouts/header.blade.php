<!DOCTYPE html>
<html lang="en">

<head>
    <title>Auricle - Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        * {
            box-sizing: 0;
        }

        a {
            text-decoration: none;
        }

        html,
        body {
            font-size: 14px;
            margin: 0;
            padding: 0;

        }

        .container {
            width: 100%
        }

        .carosel1 p {
            margin-bottom: 0;
        }

        #menuToggle {
            display: block;
            position: relative;
            /*top: 50px;
    left: 50px;*/

            z-index: 1;

            -webkit-user-select: none;
            user-select: none;
        }

        #menuToggle a {
            text-decoration: none;
            color: #232323;

            transition: color 0.3s ease;
        }

        #menuToggle a:hover {
            color: tomato;
        }


        #menuToggle input {
            display: block;
            width: 40px;
            height: 32px;
            position: absolute;
            top: -7px;
            left: -5px;

            cursor: pointer;

            opacity: 0;
            /* hide this */
            z-index: 2;
            /* and place it over the hamburger */

            -webkit-touch-callout: none;
        }

        /*
 * Just a quick hamburger
 */
        #menuToggle span {
            display: block;
            width: 33px;
            height: 4px;
            margin-bottom: 5px;
            position: relative;

            background: #183887;
            border-radius: 3px;

            z-index: 1;

            transform-origin: 4px 0px;

            transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0),
                background 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0),
                opacity 0.55s ease;
        }

        #menuToggle span:first-child {
            transform-origin: 0% 0%;
        }

        #menuToggle span:nth-last-child(2) {
            transform-origin: 0% 100%;
        }

        /* 
 * Transform all the slices of hamburger
 * into a crossmark.
 */
        #menuToggle input:checked~span {
            opacity: 1;
            transform: rotate(45deg) translate(-2px, -1px);
            background: #232323;
        }

        /*
 * But let's hide the middle one.
 */
        #menuToggle input:checked~span:nth-last-child(3) {
            opacity: 0;
            transform: rotate(0deg) scale(0.2, 0.2);
        }

        /*
 * Oh yeah and the last one should go the other direction
 */
        #menuToggle input:checked~span:nth-last-child(2) {
            transform: rotate(-45deg) translate(0, -1px);
        }

        /*
 * Make this absolute positioned
 * at the top left of the screen
 */
        #menu {
            position: fixed;
            width: 300px;
            padding: 50px;
            padding-top: 50px;
            left: -500px;
            height: 100vh;
            top: 80px;
            background: #183887;
            color: #fff;
            list-style-type: none;
            -webkit-font-smoothing: antialiased;
            /* to stop flickering of text in safari */

            transform-origin: 0% 0%;
            transform: translate(-100%, 0);

            transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1.0);
        }

        #menu li {
            padding: 10px 0;
            font-size: 22px;
            color: #fff
        }

        /*
 * And let's slide it in from the left
 */
        #menuToggle input:checked~ul {
            transform: none;
            left: 0px;

        }

        .cardText h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cardText p {
            font-size: 16px;
            text-align: justify;
        }

        .searchmodal {
            background-color: white;
            border: none;
        }

        .searchicon {
            color: black;
            cursor: pointer;
            font-size: 26px;
        }

        input::-webkit-input-placeholder {
            color: rgb(131, 131, 131);
        }

        input:-moz-placeholder {
            color: red;

        }

        .fa-magnifying-glass {
            color: #000;
            font-size: 26px;
        }

        .searchform {
            position: relative;
        }

        .searchform i {
            position: absolute;
            right: 0;
        }

        .searchform input {
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            padding: 15px;
        }

        .searchform i {
            position: absolute;
            right: 10px;
            top: 34px;
        }

        section {
            padding: 40px 0px;
        }



        .carosel1 {
            display: flex !important;
            border-radius: 15px;
            width: 100% !important;
            padding-right: 15px;
            overflow: hidden;
        }

        .imgperson {
            width: 35%;
            background: #d8fd9e;
        }

        .imgText {
            width: 65%;
            padding: 20px;
            background: #ECF5DE;
            border-radius: 0px 15px 15px 0px;
            position: relative;

        }

        .category {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .imgperson img {
            width: 100%;
            mix-blend-mode: multiply;
        }

        .username {
            color: #183887;
            font-size: 20px;
            font-weight: 700;
        }

        .seemore {
            color: #183887;
        }

        .persondescri {
            color: #333333;
            font-size: 16px;
        }

        .Auricle_Blogs {
            background: url(../images/Auricle_Blogs.png) no-repeat center;
            width: 100%;
            background-size: cover;
            background-position: top
        }

        .Auricle_BlogsTxt {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            /*padding-left: 5rem;*/
            padding-top: 7rem;
        }

        .Auricle_BlogsTxt h1 {
            color: #183887;
            font-size: 42px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            margin-bottom: 15px;
        }

        .btn-light {
            background: #fff;
            border: solid 2px #183887;
            color: #183887;
            padding: 6px 20px !important;
        }

        .btn-light:hover {
            background: #183887;
            border: solid 2px #183887;
            color: #fff;
            padding: 6px 20px !important;
        }

        .Auricle_BlogsTxt p {
            color: #000;
            font-size: 22px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .form-control {
            padding: 12px;
        }

        .carouselMain {
            padding: 30px 40px;
            border: 0px solid #c1c1c1;
            border-radius: 15px;
            background-color: white;
            box-shadow: var(--bs-box-shadow) !important;
        }

        .tag {
            background-color: #F2D797;
            position: absolute;
            right: 0;
            padding: 0px 8px;
            font-size: 9px;
            line-height: 1.9;
            font-weight: 600;
            border-radius: 15px 0px 0px 15px;
        }

        .diagnosed {
            font-size: 18px;

        }

        .persondescri {
            margin-top: 5px;
            font-size: 16px;

        }

        .seemore {
            margin-top: 15px;
            font-size: 14px;
        }

        .seemore i {
            font-size: 14px;
            margin-left: 10px;
        }

        .variable-width {
            margin-top: 30px;
        }

        .btn-success-outline {
            color: #183887;
            font-weight: 700;
        }

        .cardBlog {
            border-radius: 0.475rem;
            background-color: #F7F7F7;
        }

        .bycandidate {
            margin-top: 3rem;
            padding-top: 3rem;
            margin-bottom: 0;
        }

        .bycandidate span {
            color: #183887;
            font-weight: bold
        }

        .articleForm {
            border: 0;
            border-radius: 0.475rem;

        }

        .btn-submit {
            background-color: #183887;
            padding: 8px 55px;
            color: #fff;
            font-size: 20px;
        }

        .btn-submit:hover {
            background-color: #000;
            padding: 8px 55px;
            color: #fff
        }

        .formimg {
            width: 85%;
        }

        .footerbtm {
            background: #1B1F4D;
            color: white;
            margin-top: -15rem;
            padding-top: 12rem;
        }

        .footer {
            background: #1B1F4D;
            color: white;
        }

        .footerImg {
            display: flex;
        }

        .imggoogle {
            width: 140px;
            padding-right: 5px;
        }

        .socialMedia {
            padding: 0;
            display: flex;
        }

        .socialMedia li {
            list-style: none;
            padding-left: 15px;
        }

        .socialMedia li i {
            font-size: 16px;
        }

        .footerinput {
            display: flex;
        }

        .footerinput input {
            width: 100%;
            background-color: transparent;
            border: 1px solid #ccc;
            border-radius: 5px 0px 0px 5px;
            padding: 0px 10px;
        }

        .footerinput button {
            background: white;
            border-radius: 0px 5px 5px 0px;
        }

        .patients .card {
            border: none;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .thumbnail {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .patients .card h5 {
            font-weight: bold;
            color: #183887;
            font-size: 20px;
        }

        .patients .card p {
            font-weight: 500;
            font-size: 16px;
        }

        .patients .card a {
            font-size: 14px;
            text-decoration: none;
            color: #7A7C80;
            font-weight: 500;
            margin: 10px 0px;
            transition: 0.5s;
        }

        .patients .card a:hover {
            color: #FDB813;
            transition: 0.5s;
        }

        .breadcrum {
            margin: 0;
            padding: 0;
        }

        .breadcrum li {
            list-style: none;
            float: left;
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .over-view {
            background: #F8FAFF;
        }

        .over-view img {
            border-radius: 10px;
        }

        .topic {
            color: #F37021
        }

        .name {
            color: #183887;
            font-size: 22px;
            font-weight: bold;
        }

        .disignation {
            font-size: 16px;
            color: #525252;
        }

        .blog-pic {
            position: relative;
        }

        .blog-author {
            position: absolute;
            background: #183887;
            padding: 15px;
            left: 0;
            bottom: 0px;
            width: 100%;
            margin: 0 auto;
            border-radius: 0px 0px 15px 15px;
        }

        .blog-author img {
            border-radius: 100px;
            height: 64px;
            width: 64px;
        }

        .blog-author ul {
            margin: 0;
            padding: 0;
        }

        .blog-author li {
            float: left;
            list-style: none;
            color: #fff;
        }

        .blog-author h5 {
            font-weight: bold;
        }

        .blog-author span {
            font-size: 18px;
            font-weight: 400;
        }


        @media screen and (max-width: 620px) {

            .imgperson,
            .imgText {
                width: 100%
            }
        }
    </style>

</head>

<body>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <form class="searchform">
                        <div class="form-group">
                            <label></label>
                            <input type="text" placeholder="Search..." class="form-control">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer " style="border-top: none;">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Search</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row align-items-center mt-3 mb-3">
            <div class="col-md-4">
                <nav role="navigation">
                    <div id="menuToggle">
                        <input type="checkbox" />
                        <span></span>
                        <span></span>
                        <span></span>
                        <ul id="menu">
                            <a href="#">
                                <li>Home</li>
                            </a>
                            <a href="#">
                                <li>About</li>
                            </a>
                            <a href="#">
                                <li>Info</li>
                            </a>
                            <a href="#">
                                <li>Contact</li>
                            </a>
                            <a href="https://erikterwan.com/" target="_blank">
                                <li>Show me more</li>
                            </a>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="col-md-4 text-center">
                <img src="images/logo.png" class="img-fluid">

            </div>
            <div class="col-md-4  text-end">
                <div class="searchicon" data-bs-toggle="modal" data-bs-target="#myModal">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

        </div>
    </div>
    <div class="Auricle_Blogs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="Auricle_BlogsTxt">
                        <h1>Auricle Blogs</h1>
                        <p>Personal Stories. Expert Perspectives.</p>
                    </div>
                </div>
                <div class="col-md-5" style="mix-blend-mode: multiply">
                    <img src="images/blog-hero.png" class="img-fluid" alt="Auricle Blog" />
                </div>
            </div>
        </div>
    </div>

    @yield('content')

  
    <section class="footerbtm pb-3">
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-4">
                    <p>Download Auricle Mobile Application</p>
                    <div class="footerImg">
                        <img src="images/imggoogle.png" class="imggoogle">
                        <img src="images/imgApple.png" class="imggoogle">
                    </div>
                    <p class="mt-3">Follow Auricle on Social Media</p>
                    <ul class="socialMedia">
                        <li><i class="fa-brands fa-facebook-f"></i></li>
                        <li><i class="fa-brands fa-instagram"></i></li>
                        <li><i class="fa-brands fa-twitter"></i></li>
                        <li><i class="fa-brands fa-linkedin-in"></i></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <p>Any Questions?</p>
                    <button class="btn btn-outline-warning"> Contact Us </button>
                </div>
                <div class="col-md-4 ms-auto">
                    <p>Sign up for our free Auricle Daily Newsletter</p>
                    <form>
                        <div class="form-group footerinput">
                            <input type="text" placeholder="Enter your Email Address...">
                            <button class="btn btn-subscribe">Subscribe</button>
                        </div>
                    </form>
                    <p class="mt-3">By clicking Subscribe, I agree to the Auricle Terms & Conditions & Privacy
                        Policy and
                        understand
                        that I may opt out of Auricle subscriptions at any time.</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">© 2024 All Rights Reserved by Auricle.co.in</p>
            </div>
        </div>
    </section>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('.variable-width').slick({
                slidesToShow: 2.5,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                speed: 300,
                infinite: true,
                autoplaySpeed: 5000,
                autoplay: true,
                responsive: [{
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        });
    </script>
</body>

</html>