<?php
include("config.php");
?>
<?php
// function sanitize_output($buffer)
// {
    // $search = array(
        // '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        // '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        // '/(\s)+/s'       // shorten multiple whitespace sequences
        // );
    // $replace = array(
        // '>',
        // '<',
        // '\\1'
        // );
    // $buffer = preg_replace($search, $replace, $buffer);
    // return $buffer;
// }
// ob_start("sanitize_output");
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!--Meta-->	
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="theme-color" content="#4A90E2" />
	<meta name="description" content="Enjoy low fees when you send money to your friend or family. Transfer money directly to a bank account the choice is yours.">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="robots" content="index, follow"/>
	<!--Favicon-->
	<link rel="icon" href="favicon.ico">

    <meta name="robots" content="index,follow"/>
	 <link rel="canonical" href="https://www.koofamilies.com/"/>
	<!-- Title-->
	<title>Transfer Money | Send and Earn Money Online | Koo Families</title>
		<!--Google fonts-->
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700%7COpen+Sans:400,600,700" rel="stylesheet">
  
	<!--Icon fonts-->
	<link rel="stylesheet" href="<?php echo $site_url; ?>/assets/vendor/strokegap/style.css">
	<link rel="stylesheet" href="<?php echo $site_url; ?>/assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $site_url; ?>/assets/vendor/linearicons/style.css">
   <link rel="stylesheet" href="<?php echo $site_url; ?>/assets/css/bundle.css">
  <link rel="stylesheet" href="<?php echo $site_url; ?>/assets/css/style.css">
  	<!-- Manifest -->
	<link rel="manifest" href="manifest.json">
  
  
  <style>
    
  .border {
    border: 1px solid #e9ecef00!important;}
.logo{
    color: #fff;
    font-size: 37px;
}
.u-mt-90 {
    margin-top: 10rem !important;
}
 .step-number {
    left: 20px;
    width: 50px;
    height: 50px;
    padding-top: 2px;
    font-size: 32px;
    font-weight: 200;
    text-align: center;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 50%;
}
.label{
    color: #fff;
    font-size: 23px;
}
.label_p{
    color: #fff;
    font-size: 23px;
} 

.select{
    border: 1px solid #e9eff5;
    padding: 9px 30px;
    border-radius: 4px;
    width: 100%;
    margin-bottom: 10px;
}
.display-4{
    font-size: 2rem;
}

<!-- Edited by Sumit  -->
@media (min-width:200px) and (max-width:767px){
.nav-link {
    display: block;
    padding: 0px;
}
.nav .dropdown-toggle {
    font-size: 13px !important;
}
.nav .dropdown-toggle img {
    width: 25px !important;

}
}
<!-- Edited by Sumit  -->
 </style>
 
 
 
 
 
 
 
 
 <style>
            /* jssor slider loading skin spin css */
            .jssorl-009-spin img {
                animation-name: jssorl-009-spin;
                animation-duration: 1.6s;
                animation-iteration-count: infinite;
                animation-timing-function: linear;
            }

            @keyframes jssorl-009-spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>
		
		
		
		
		

</head>

 <body id="top">
 
 
<!--[if lt IE 8]>
<p>You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->



 <header class="header header-shrink header-inverse fixed-top">
  <div class="container">
		<nav class="navbar navbar-expand-lg px-md-0">
			<a class="navbar-brand" href="index.php">
				<span class="logo-default">
					<h3 class="logo">KooFamilies</h3>
				</span>
				<span class="logo-inverse">
					<h3>KooFamilies</h3> 
				</span>
			</a>
            <!--<div class="navbar-toggler" data-toggle="collapse" data-target="#navbarLanguage" style="font-size: 1rem; padding: .25rem 0.05rem;">
				<span>Language</span>
			</div>-->
			<?php if(!isset($_SESSION['login'])){	?>
			        <a class="nav-link" href="login.php">Login</a>
			<?php } else {?>
			        <a class="nav-link" href="dashboard.php">Dashboard</a>
			<?php }?>
		
			</div>
            
			<div class="collapse navbar-collapse" id="navbarLanguage">
			    <ul class="navbar-nav ml-auto">
			        <li class="nav-item active">
			            <a href="?language=english" class="nav-link">English</a>
			        </li>
			        <li class="nav-item dropdown">
			            <a href="?language=chinese" class="nav-link">Chinese</a>
			        </li>
			        <li class="nav-item dropdown">
			            <a href="?language=malaysian" class="nav-link">Malay</a>
			        </li>
			    </ul>
			</div>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
				
					<?php
					if(!isset($_SESSION['login']))
					{
					?>
					<li class="nav-item active">
						<a class="nav-link" href="login.php">Login</a>
					</li>
					<?php
					}
					else
					{
					?>
					<li class="nav-item dropdown">
						<a class="nav-link" href="dashboard.php">Dashboard
						</a>
					</li>
					<?php
					}
					?>
				
				</ul>
			</div>

		</nav>
  </div> <!-- END container-->
</header> <!-- END header -->

<section class="u-py-100 u-h-100vh u-flex-center" style="  background-image: -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: linear-gradient(0deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), linear-gradient(0deg, #113a82 0%, #157af9 100%);
    -webkit-background-size: cover;
    background-size: cover;">
  <div class="container">

   
   
   
   
   
   <div id="slider1_container" style="visibility: hidden; position: relative; margin: 0 auto; width: 1140px; height: 442px; overflow: hidden;">
            <!-- Loading Screen -->
            <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
                <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="../svg/loading/static-svg/spin.svg" />
            </div>

            <!-- Slides Container -->
            <div data-u="slides" style="position: absolute; left: 0px; top: 0px; width: 1140px; height: 442px;
            overflow: hidden;">
				<div>
                    <iframe src="https://www.youtube.com/embed/6KrFhmmA12s" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
                <div>
                    <iframe src="https://www.youtube.com/embed/kJH-GUP1oFg" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
                <div>
                    <iframe src="https://www.youtube.com/embed/44AlXFViVOk" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
                <div>
                    <iframe src="https://www.youtube.com/embed/0IPWi-cNqss" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
                <div>
                    <iframe src="https://www.youtube.com/embed/uf3bzjE4fuY" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
				<div>
                    <iframe src="https://www.youtube.com/embed/0IPWi-cNqss" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
            </div>
            
            <!--#region Bullet Navigator Skin Begin -->
            <!-- Help: https://www.jssor.com/development/slider-with-bullet-navigator.html -->
            <style>
                .jssorb031 {position:absolute;}
                .jssorb031 .i {position:absolute;cursor:pointer;}
                .jssorb031 .i .b {fill:#000;fill-opacity:0.5;stroke:#fff;stroke-width:1200;stroke-miterlimit:10;stroke-opacity:0.3;}
                .jssorb031 .i:hover .b {fill:#fff;fill-opacity:.7;stroke:#000;stroke-opacity:.5;}
                .jssorb031 .iav .b {fill:#fff;stroke:#000;fill-opacity:1;}
                .jssorb031 .i.idn {opacity:.3;}
            </style>
            <div data-u="navigator" class="jssorb031" style="position:absolute;bottom:12px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
                <div data-u="prototype" class="i" style="width:16px;height:16px;">
                    <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                        <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                    </svg>
                </div>
            </div>
            <!--#endregion Bullet Navigator Skin End -->
        
            <!--#region Arrow Navigator Skin Begin -->
            <!-- Help: https://www.jssor.com/development/slider-with-arrow-navigator.html -->
            <style>
                .jssora051 {display:block;position:absolute;cursor:pointer;}
                .jssora051 .a {fill:none;stroke:#fff;stroke-width:360;stroke-miterlimit:10;}
                .jssora051:hover {opacity:.8;}
                .jssora051.jssora051dn {opacity:.5;}
                .jssora051.jssora051ds {opacity:.3;pointer-events:none;}
            </style>
            <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
                <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
                </svg>
            </div>
            <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
                <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
                </svg>
            </div>
            <!--#endregion Arrow Navigator Skin End -->
        </div>
   
   
   
       <div class="row">
      <div class="col-8" style="margin-top:10px;">
        <h2 class="display-4 u-fw-600 text-white u-mb-40">
                Everyday, we create one more hour in your life through food ordering platform.
        </h2>
        <h1 class="display-4 u-fw-600 text-white u-mb-40">
         Safe, Easy, Immediate Payment and Earn Income Everyday
        </h1>
        <p class="u-fs-22 text-white u-lh-1_8 u-mb-40">
		Give us a united safe and convenient society.
        </p>
		<a href="login.php" style="background-color:#0659A2;color:#FFFFFF" class="btn btn btn-rounded btn-primary m-2 px-md-5 py-3">
        	 GET START 
        </a>
      </div> 
		 <div class="col-4 text-center"><img src="<?php echo $site_url; ?>/img/mobile.png"class="img-responsive" alt=""></div>

     
        <!-- END col-lg-6-->
    </div> <!-- END row-->
    
    
  </div> <!-- END container-->
</section> <!-- END intro-hero-->
<section>
	<div class="container">
		<h2 class="h1 text-center">
		Three steps to pay / transfer money / earn income 
		</h2>
		<div class="row align-items-center">			
			<div class="col-lg-6">
			<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">1</span>
					<div class="media-body">
						<h4 class="h4">
							<a href="login.php">Sign Up</a>
						</h4>
						<p>
							Sign up for a free Koopay. Free wallet on web, iOS or Android and through easy verification process.
						</p>
					</div>
				</div>
			<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">2</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Deposit money</a>
						</h4>
						<p>
							Select your preferred deposit method like bank and deposit money into your own wallet. 
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">3</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Make payment / transfer / withdraw money (Free of charge)</a>
						</h4>
						<p>
							You can easily make payment or transfer money through 100% secure method of authorisation code or finger print. 
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">4</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Earn Income</a>
						</h4>
						<p>
							You can start to earn money by advertising on your social networks. And you can use the money to pay for your bills.
						</p>
					</div>
				</div>
				<div class="media mt-4">
				<span class="step-number text-primary u-fs-28 mr-3 mt-2">5</span>
					<div class="media-body">
						<h4 class="h4">
						<a href="login.php">Donation </a>
						</h4>
						<p>
							If you like our idea and would like to donate to our team to improve the society. Or you can  
	Join our Crowdfunding: 

						</p>
					</div>
				</div>
			</div> <!-- END col-lg-6 -->
			<div class="col-lg-5 ml-auto text-center">
				<img class="wow fadeInRight w-100 rounded" src="<?php echo $site_url; ?>/img/mobile1.png" alt="">
			</div> <!-- END col-lg-6 pl-lg-5 -->
		</div> <!-- END row-->
	</div> <!-- END container-->
</section>

		<div class="scroll-top bg-white box-shadow-v1">
			<i class="fa fa-angle-up" aria-hidden="true"></i>
		</div> 

		<script src="<?php echo $site_url; ?>/assets/js/bundle.js" defer type="text/javascript"></script>
		<script src="<?php echo $site_url; ?>/assets/js/fury.js" defer type="text/javascript"></script>
		
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript' async='async' defer='defer'>
(function(){ var widget_id = 'QCJcJ4Qb9Q';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = 'https://code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
	
  </body>	
  <script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/pwa-sw.js').catch(function(err) {
        console.log("Service Worker error: ", err)
      });
  }
</script>








<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="slider/jquery-1.9.1.min.js"></script>
    <script src="slider/bootstrap.min.js"></script>
    <script src="slider/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="slider/ie10-viewport-bug-workaround.js"></script>

    <!-- jssor slider scripts-->
    <script type="text/javascript" src="slider/jssor.slider.min.js"></script>
    <script>

        jQuery(document).ready(function ($) {
            var options = {
                $AutoPlay: 1,                                       //[Optional] Auto play or not, to enable slideshow, this option must be set to greater than 0. Default value is 0. 0: no auto play, 1: continuously, 2: stop at last slide, 4: stop on click, 8: stop on user navigation (by arrow/bullet/thumbnail/drag/arrow key navigation)
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $Idle: 5000,                                        //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: 1,   			                //[Optional] Steps to go for each navigation request by pressing arrow key, default value is 1.
                $SlideEasing: $Jease$.$OutQuint,                    //[Optional] Specifies easing for right to left animation, default value is $Jease$.$OutQuad
                $SlideDuration: 800,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide, default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $Cols is greater than 1, or parking position is not 0)

                $ArrowNavigatorOptions: {                           //[Optional] Options to specify and enable arrow navigator or not
                    $Class: $JssorArrowNavigator$,                  //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                },

                $BulletNavigatorOptions: {                          //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                 //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                    $SpacingX: 12,                                  //[Optional] Horizontal space between each item in pixel, default value is 0
                    $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                }
            };

            var jssor_slider1 = new $JssorSlider$("slider1_container", options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
                if (parentWidth) {
                    jssor_slider1.$ScaleWidth(parentWidth - 30);
                }
                else
                    window.setTimeout(ScaleSlider, 30);
            }
            ScaleSlider();

            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
    </script>
</html>
