<!DOCTYPE html>
<html lang="en">
<head>
 
  <!--Meta-->
  <meta charset="UTF-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="A complete landing page solution for any business">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!--Favicon-->
  <link rel="icon" href="">
  
  <!-- Title-->
  <title>koofamilies</title>
  
  <!--Google fonts-->
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700%7COpen+Sans:400,600,700" rel="stylesheet">
  
	<!--Icon fonts-->
	<link rel="stylesheet" href="assets/vendor/strokegap/style.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendor/linearicons/style.css">
  
  <!-- Stylesheet-->
  <!--
// ////////////////////////////////////////////////
// To Reduce server request and improved page speed drastically all third-party plugin bundle in assets/css/bundle.css
// If you wanna add those manually bellow is the sequence 
// ///////////////////////////////////////////////
-->
<!--  <link rel="stylesheet" href="assets/vendor/bootstrap/dist/css/bootstrap.min.css">  
  <link rel="stylesheet" href="assets/vendor/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="assets/vendor/fancybox/dist/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/vendor/animate.css/animate.min.css">-->
  
  <link rel="stylesheet" href="assets/css/bundle.css">
  <link rel="stylesheet" href="assets/css/style.css">
  
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



.ln-card {
    display: block;
    margin-top: 2px;
    margin-bottom: 15px;
    background: #fff;
    border-radius: 2px;
    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .24), 0 0 2px 0 rgba(0, 0, 0, .12);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .24), 0 0 2px 0 rgba(0, 0, 0, .12);
}
.ln-card .ln-card-inner {
    padding: 30px 30px 20px 30px;
}
.ln-card .ln-card-inner h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
    font-weight: 500;
    line-height: 32px;
    color: #12326b;
}
.ln-card .ln-card-inner p, a.ln-card .ln-card-inner {
    color: #757575;
}
.ln-card .ln-card-inner p {
    margin-bottom: 10px;
}
.city{
    padding: 36px;
    margin-bottom: 16px;
    margin-top: 14px;

}
 </style>
</head>

 <body id="top">

 <header class="header header-shrink header-inverse fixed-top">
  <div class="container">
		<nav class="navbar navbar-expand-lg px-md-0">
			<a class="navbar-brand" href="index.php">
				<span class="logo-default">
					<h3 class="logo">koofamilies</h3>
				</span>
				<span class="logo-inverse">
					<h3>koofamilies</h3>
				</span>
			</a>

			<div class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="lnr lnr-text-align-right nav-hamburger"></span>
				<span class="lnr lnr-cross nav-close"></span>
			</div>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active">
						<a class="nav-link" href="about.php">About Us</a>
					</li>
					<?php
					if(!isset($_SESSION['login']))
					{
					?>
					<li class="nav-item active">
						<a class="nav-link" href="login.php">Login</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link" href="login.php">Sign Up
						</a>
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
					
					<li class="nav-item dropdown">
						<a class="nav-link" href="#">Download App</a>
					</li>
				</ul>
			</div>

		</nav>
  </div> <!-- END container-->
</header> <!-- END header -->








<section class="u-py-40 u-flex-center" style="  background-image: -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: linear-gradient(0deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), linear-gradient(0deg, #113a82 0%, #157af9 100%);
    -webkit-background-size: cover;
    background-size: cover;
">
  <div class="container">
   
    <div class="row">
      <div class="col-12 u-mt-90" style="">
        <h1 class="display-4 u-fw-600 text-white u-mb-40">
      About koofamilies
        </h1>
        <p class="u-fs-22 text-white u-lh-1_8 u-mb-40">
“  The greatest contribution by “ Jack Ma ” is successfully eliminating all snatch thief and robbery in China (because of it’s Alipay/Wechat, a cashless society). This is what we, all Malaysian need for now “a cashless society to eliminate all snatch thief and robbery in our society.” 
        </p>
		
      </div> 
		 

     
        <!-- END col-lg-6-->
    </div> <!-- END row-->
    
    
  </div> <!-- END container-->
</section> <!-- END intro-hero-->

	
	<section>
  <div class="container-fluid">
   <div class="row align-items-center">
  
		<div class="col-lg-6 ml-auto my-4">
		
			<img src="img/abouts_image.jpg" alt="">
		</div> <!-- END col-md-6-->
		<div class="col-lg-5 ml-auto mt-5 mb-4">
			<h2>Features</h2>
			<ol>
				<li>You can pay bills and transfer money (Free of Charge)</li>
				<li>All transactions are highly secured and protected with authorisation code or finger print.</li>
				<li>You can earn daily income through advertisement in your social networks.</li>
				<li>You can top up your Koopay / Wechat / Alipay with attractive rate 1.62, please find out here.</li>
			</ol>
			
		</div> <!-- END col-md-6-->
   </div> <!-- END 	row-->

   <!-- END 	row-->

    <!-- END 	row-->
  </div> <!-- END container-->
</section>

<section class="u-py-100 u-flex-center" style="  background-image: -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: linear-gradient(0deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -webkit-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), -o-linear-gradient(90deg, #113a82 0%, #157af9 100%);
    background-image: url(img/circles.png), linear-gradient(0deg, #113a82 0%, #157af9 100%);
    -webkit-background-size: cover;
    background-size: cover;
">
  <div class="container">
    <div class="row text-center">
     
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
			<img src="img/astro.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
          <img src="img/celcom.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
      <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/grab.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/maxis.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
      
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/syabas.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
	  
	  <div class="col-lg-3 col-md-4 u-mt-30">
        <div class="bg-white px-4 py-5 px-md-5 u-h-100p rounded box-shadow-v1">
         <img src="img/tenaga.png" style="width:100%">
        </div>
      </div> <!-- END col-lg-4 col-md-6-->
	  
    </div> <!-- END row-->
  </div> <!-- END container-->
</section>


<footer>
	<section class="">
		<div class="container">
		<div class="row">
			<div class="col-lg-6 mb-5 mb-lg-0">
				<h2>koofamilies</h2>
				<p class="u-my-40">
					Safe, Easy, Immediate Payment and Earn Income Everyday.
					Build a cashless society, Give us a Safe and Integrity Society,
					( Your money is guaranteed by Certified Lawyer of Malaysia )
				</p>
			</div>
			<div class="col-lg-6 ml-auto mb-5 mb-lg-0">
				<h4>Contact Info</h4>
				<div class="u-h-4 u-w-50 bg-primary rounded mt-3 u-mb-40"></div>
				<ul class="list-unstyled">
					<li class="mb-2">
						<span class="icon icon-Phone2 text-primary mr-2"></span><a href="tel:+607-6626205">+607-6626205</a>, <a href="tel:+012-3115670">+012-3115670</a>
					</li>
					<li class="mb-2">
						<span class="icon icon-Mail text-primary mr-2"></span> <a href="mailto:info@koopay.com">info@koopay.com</a>
					</li>
					<li class="mb-2">
						<span class="icon icon-Pointer text-primary mr-2"></span>Kemajuaan ladang Cermerlang Sdn. Bhd. 
          1400, Jalan Lagenda 50, Taman Lagenda Putra
          Kulai, Johor, 81000, Malaysia

					</li>
				</ul>
			</div>
		</div> <!-- END row-->
	</div> <!-- END container-->
	</section> <!-- END section-->
	
	
	<section class="u-py-40">
		<div class="container">				
			<p class="mb-0 text-center"> 
				© Copyright 2017  -  Created by <a class="text-primary" href="#" target="_blank">koofamilies</a>
			</p>
		</div>
	</section>
</footer>
     
<div class="scroll-top bg-white box-shadow-v1">
	<i class="fa fa-angle-up" aria-hidden="true"></i>
</div> 
		

<!--
// ////////////////////////////////////////////////
// To Reduce server request and improved page speed drastically all third-party plugin bundle in assets/js/bundle.js
// If you wanna add those manually bellow is the sequence 
// ///////////////////////////////////////////////
-->
<!--
		<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
		<script src="assets/vendor/popper.js/dist/popper.min.js"></script>
		<script src="assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="assets/vendor/slick-carousel/slick/slick.min.js"></script>
		<script src="assets/vendor/fancybox/dist/jquery.fancybox.min.js"></script>
		<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
		<script src="assets/vendor/isotope/dist/isotope.pkgd.min.js"></script>
		<script src="assets/vendor/parallax.js/parallax.min.js"></script>
		<script src="assets/vendor/wow/dist/wow.min.js"></script>
		<script src="assets/vendor/vide/dist/jquery.vide.min.js"></script>
		<script src="assets/vendor/typed.js/lib/typed.min.js"></script>
		<script src="assets/vendor/appear-master/dist/appear.min.js"></script>
		<script src="assets/vendor/jquery.countdown/dist/jquery.countdown.min.js"></script>
		<script src="assets/js/smoothscroll.js"></script>
-->
	
		<script src="assets/js/bundle.js"></script>
		<script src="assets/js/fury.js"></script>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'QCJcJ4Qb9Q';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->		
  </body>	
</html>
