<?php 
session_start();
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
function ceiling($number, $significance = 1)
								{
									return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
								}
 $_SESSION['mm_id']= $loginidset;
 $merchant_data= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$loginidset."'"));
 $pos_type=$merchant_data['pos_type'];
//print_r($_SESSION['remark_data']);
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title>POS</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="pos/theme.css" type="text/css"/>
    <link rel="stylesheet" href="pos/style.css" type="text/css"/>
    <link rel="stylesheet" href="pos/posajax.css" type="text/css"/>
	<link rel="stylesheet" href="pos/bootstrap.min.css">
		<link rel="stylesheet" href="pos/jquery.numpad.css">
<script src="pos/jquery.min.js"></script>
<script src="pos/bootstrap.min.js"></script>
<script src="./Dashboard_files/popper.min.js.download"></script>
  

<script src="./Dashboard_files/isotop.min.js"></script>

	
    <script type="text/javascript" src="pos/jquery-migrate-1.2.1.min.jsg"></script>
     <script type="text/javascript" src="pos/jquery.keyboard.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.extension-all.min.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
	 <script type="text/javascript" src="pos/jquery.numpad.js"></script>

	
  <script type="text/javascript">
      var subproducts_global = [];
      var products_id_global = [];
  </script>
    <script type="text/javascript">
			// Set NumPad defaults for jQuery mobile. 
			// These defaults will be applied to all NumPads within this document!
			$.fn.numpad.defaults.gridTpl = '<table class="table modal-content"></table>';
			$.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in"></div>';
			$.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" />';
			$.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-default"></button>';
			$.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="width: 100%;"></button>';
			$.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('btn-primary');};
			
			
		</script>
		<style type="text/css">
			.nmpd-grid {border: none; padding: 20px;}
			.nmpd-grid>tbody>tr>td {border: none;}
			.nmpd-grid .numero, .nmpd-grid .btn {
				padding: 25px 40px;
				font-size: 30px;
			}
			input.nmpd-display {
				text-align: right;
				height: 90px;
				font-size: 35px;
			}
		</style>
  
     <style>
    body.noscroll{
      overflow: hidden !important;
      position: fixed;
    }
    .other_products {
    display: flex;
}
    .create_date
    {
      float: right;
    }

    .comment_box {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-top: 15px;
    box-shadow: 0 0 5px 0px;
  }
    .submit_button
    {
      width:25% !important;
    }
    .comment{
      width:90%;
    }
  .well
  {
  
    min-height: 20px;
    background-color: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .well {
    width: 100% !important;
    min-height: 20px;
    background-color: transparent!important;
    border: 0px solid #e3e3e3!important;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}
  .well form{
      min-height: 280px;
  }
  .pro_name
  {
   text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
    height: 60px;
    }
    .about_mer {
    width: 100%;
}

 .input-controls {
      margin-top: 10px;
      border: 1px solid transparent;
      border-radius: 2px 0 0 2px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      height: 32px;
      outline: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    #searchInput {
      background-color: #fff;
      font-family: Roboto;
      font-size: 15px;
      font-weight: 300;
      margin-left: 12px;
      padding: 0 11px 0 13px;
      text-overflow: ellipsis;
      width: 50%;
    }
    #searchInput:focus {
      border-color: #4d90fe;
    }
    input.quatity {
      width: 90px;
    }
    .common_quant {
        display: flex;
    }
    p.quantity {
        margin-top: 7px;
    }
    .order_product{
        margin-top: 15px;
        margin-left: 10px;
        font-size: 20px;
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 10px;
    }
    .comm_prd{
        border: 1px #000000 solid;
        padding-left: 15px;
        margin-bottom: 10px;
    }
    .mBt10{
        margin-bottom: 10px;
    }
@media only screen and (max-width: 767px) and (min-width: 300px)  {
    .new_grid{
      grid-template-columns: 1fr 1fr !important;
    }

    .text_add_cart {
        background: #003A66;
        width: 109px;
        text-align: center;
        padding: 10px;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        border-radius: 8px;
        margin-left: -10px;
    }
   .master_category_filter{
        font-size: 1.2rem;
        line-height: 0.8rem;
        margin-bottom: 5px !important;
        padding: 0.5rem 0.5rem;
    }
    .category_filter{
        font-size: 1.2rem;
        line-height:0.8rem;
        margin-bottom: 5px !important;
        padding: 0.4rem 0.9em;
    }
    .order_product{
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 18px;
        padding-left: 5px;
        padding-right: 5px;
    }
    .oth_pr{
        margin-top: 20px !important;
    }

}
.nature_image {
   width: 40px;
   height: 40px;
}


@media only screen and (max-width: 600px) and (min-width: 300px)  {

  .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }

    .oth_pr{
        margin-top: 26px!important;
    padding: 6px!important;
    }
}

@media only screen and (max-width: 500px) and (min-width: 400px)  {
     .well{
        padding-top: 0px !important;
     }
     .pro_name {
         font-size: 18px;
         margin: 10px 0px 0px;
         height: 35px;
     }
     .set_calss.input-has-value {
        width: 180px;
     }
     
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
  .new_grid{
    grid-template-columns: 1fr 1fr !important;
  }
     .well{
        padding-top: 0px !important;
     }
h4.head_oth {
    font-size: 20px;
}
     .pro_name {
        text-align: center;
        font-size: 14px;
        overflow: hidden;
        /* white-space: nowrap; */
        height: auto;
        /* width: 100px; */
        line-height: 15px;
     }
     .text_add_cart {
         margin: 5px 0px;
         padding: 7px;
     }
     .common_quant {
        display: block;
     }
     .text_add_cart {
         background: #003A66;
         width: 109px;
         text-align: center;
         padding: 10px;
         color: #fff;
         text-transform: uppercase;
         font-weight: 600;
         cursor: pointer;
         /* margin-right: 8px; */
         border-radius: 8px;
         margin-left: -10px;
     }
     .mBt10{
         margin-bottom: 2px;
     }
     .nature_image {
       width: 25px;
       height: 25px;
    }
    .starting-bracket{
        margin-top: 0.8rem;
    }
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }
   .text_add_cart {
        padding: 6px;
   }

   .row#main-content {
        margin-right: 0px;
        margin-left: 0px;

    }
    .oth_pr{
  height: 40px;
  }
}
@media only screen and (max-width: 1050px) and (min-width: 992px)  {
   .text_add_cart{width: 100px}
   .text_add_cart {
       width: 125px;
       margin: 0 auto;
   }
   p.quantity {
        margin-left: 35px;
   }
   .common_quant {
        display: block;
   }
   input.quatity {
        width: 130px;
   }
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
   .set_calss.input-has-value {
        width: 173px;
   }
   .about_uss {
        width: 165px;
   }
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
   input.btn.btn-block.btn-primary.submit_button {
        width: 100%!important;
   }
   p.test_testing {
        margin: 2px;
   }
   .text_add_cart {
        margin: 5px auto;
   }
   input.quatity {
        width: 118px;
   }
   .well {
        min-height: 20px;
        padding: 0px 0 0;
   }
   .common_quant {
        display: block;
   }
   .set_calss.input-has-value {
        width: 160px;

   }
   .grid.row {
        margin-left: 18px;
   }
   p {
        margin-bottom: 0;
   }
}

@media only screen and (max-width: 800px) and (min-width: 750px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   .common_quant {
        display: block;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 800px) and (min-width: 650px)  {
   .common_quant {
        display: block;
   }
   .text_add_cart {
        width: 142px;
   }
}

/* Edited by Sumit */
@media (min-width:768px) and (max-width:1150px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  label {
      font-weight: 600;
      width: 100%;
  }
  .fjhj br {
      display: none;
  }
  .master_category_filter{
      background-color: #545c73;
      border-color: #4a5368;
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
  }
  .master_category_filter:focus, .master_category_filter.focus {
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
  }
  .master_category_filter:hover {
      color: #fff;
      background-color: #4a5368;
      border-color: #545c73;
  }
}
@media (min-width:200px) and (max-width:767px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .fjhj br {
      display: none;
  }
}

.fjhj br {
    display: none;
}
label {
    font-weight: 600;
    width: 100%;
}
/* Edited by Sumit  */
.introduce-remarks{
  height: 28px;  
  line-height: 12px;
}
#ProductModel .introduce-remarks{
  height: auto;
}
input[name='p_total[]'],input[name='p_price[]']{
  text-align: right;
}

/* Style for new products layout */

.new_grid{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 40px;
  grid-row-gap: 20px;
}

/* End of Style for new products layout */

/* Style for remarks */

.extra-price-ingredient{
  position: absolute;
  top:-15px;
  right: -15px;
  background: #1dd800;
  color: white;
  width: 40px;
  height: 40px;
  z-index: 1000;
  display: grid;
  vertical-align: middle;
  align-content: center;
  text-align: center;
  border-radius: 50%;
}
#remarks_area .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#remarks_area .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#remarks_area .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
/*End of style for remarks*/
.product_button span{
  background: #003A66;
  text-align: center;
  padding: 10px;
  color: #fff;
  font-size: 10px;
  text-transform: uppercase;
  font-weight: 500;
  cursor: pointer;
  border-radius: 8px;
  display: inline-block;
  margin-left:20px;
  padding:10px;
}

  .ingredient{
    border: 1px solid #03a9f3;
    color :#03a9f3;
    width: 95%;
    border-radius: 5px;
    padding: 3px;
    box-sizing: border-box;
    letter-spacing: 1px;
    margin: 8px 0;
    -webkit-touch-callout: none; 
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none; 
    -ms-user-select: none; 
    user-select: none;
  }
  .ingredient span:nth-child(even){
    padding-left: 10px;
    font-weight: bold;
  }
  #ingredients_container{
    display: grid;
    grid-template-columns: 1fr 1fr;
  }
  .credentials-container{
    width: 60%;
    
  }
  .credentials-container > div{
    display: grid;
    grid-template-columns: 2fr 3fr;
    grid-column-gap: 5px;
    grid-row-gap: 5px;
    margin-bottom: 10px;
  }
  .credentials-container > div > *{
    width: 100%;
  }
  #reg_field,#passwd_field{
    display: block;
    margin-top: 10px;
    width: 40%;
/*    grid-column-start: 1;
    grid-column-end: 3;
    grid-column-gap: 10px;
    grid-row-gap: 2px;
*/  }
  @media (max-width: 767px) {
  #remarks_area .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #ProductModel > .modal-dialog{
    max-width: 90%;
   } 
   .credentials-container{
      width: 100%;
      margin-bottom: 20px;
    }
    .credentials-container > div{
      grid-template-columns: 1fr;
    }
    #reg_field{
      grid-template-columns: 1fr;
    }
    #passwd_field > input{
      grid-column-start: 1 !important;
      grid-column-end: 3 !important;
    }
    #reg_field, #passwd_field{
      width: 100%;
    }
  }
  input[type='submit'][disabled],button[disabled]{
    cursor: not-allowed;
  }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
      /* display: none; <- Crashes Chrome on hover */
      -webkit-appearance: none;
      margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
  }
  #login_passwd_modal .login_main button.btn{
    width: 100%;
  }
  #login_passwd_modal .login_main .row{
    margin-bottom: 20px;
  }
  @media (max-width: 767px) {
   #ProductModel > .modal-dialog{
    max-width: 90%;
   } 
  }
  body.noscroll{
      overflow: hidden !important;
      position: fixed;
    }

#remarks_area .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #remarks_area .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
}
#SectionModel .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#SectionModel .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #SectionModel > .modal-dialog{
    max-width: 90%;
   } 
  </style>
    <style type="text/css">
        .with_varient{background-color:skyblue;}
        .btn-work{ text-align: center; }

        * {
  box-sizing: border-box;
}
.posdel{font-size:25px !important;}

.example input[type=text] {
  padding: 5px;
  font-size: 14px;
  border: 1px solid #009c7c;
  float: left;
  width: 80%;
  background: #f1f1f1;
}

.example button {
  float: left;
  width: 15%;
  padding: 5px;
  background: #009c7c;
  color: white;
  font-size: 15px;
  border: 1px solid #009c7c;
  border-left: none;
  cursor: pointer;
}
.bnt {
  background-color: #f0ad4e;
  border: none;
  border-radius:12px;
  color: white;
  padding: 5px 10px;
  text-align: center;
  font-size: 13px;
  
}

.bnt_add {
  background-color: #51d2b7;
  border: none;
  border-radius:12px;
  color: white;
  padding: 5px 10px;
  text-align: center;
  font-size: 53px;
  
}
 .bnt_pro {
      background: #003A66;
    text-align: center;
    padding: 10px;
    color: #fff;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: 500;
    cursor: pointer;
    border-radius: 8px;
    display: inline-block;
    margin-left: 20px;
    padding: 10px;
  
}  
 .bnt_remark {
      background: #fb9678;
    text-align: center;
    padding: 10px;
    color: #fff;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: 500;
    cursor: pointer;
    border-radius: 8px;
    display: inline-block;
    margin-left: 20px;
    padding: 10px;
  
}  
    .modal-content .close
    {
      font-size: 1.48571em;
    top: -0.5714em;
    position: absolute;
    right: -0.85714em;
    height: 2em;
    width: 2em;
    background-color: #313a46;
    opacity: 1;
    border: 2px solid #ffffff;
    text-shadow: none;
    color: #ffffff;
    border-radius: 50%;
    text-align: center;
    line-height: 1.83333em;
}
.save_close
{
display: inline-block;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.9625rem 1.2em;
    font-size: 15px;
    line-height: 1.57143;
    border-radius: 0.25rem;
    color: #fff;
    background-color: #38d57a;
    border-color: #38d57a;
	height:40px;
}
.cancel_close
{

    display: inline-block;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.8625rem 1.2em;
    font-size: 14px;
    line-height: 1.57143;
    border-radius: 0.25rem;
    color: #fff;
    background-color: #e6614f;
    border-color: #e6614f;
}
.manu_btn
{
  
  display: inline-block;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.8625rem 1.2em;
    font-size: 14px;
    line-height: 1.57143;
    border-radius: 0.25rem;
    color: #000;
    background-color: #e4e7ea;
    border-color: #e4e7ea;
}

#remarks_area .btns.btn_sec{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#remarks_area .btns.btn_sec.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#remarks_area .btn.btn-secondary.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}

.btns {
     display: inline-block;
    font-weight: 400;
    user-select: none;
    border: 1px solid transparent;
    padding: 12PX;
    padding-right: 16px;
    font-size: 15px;
    line-height: 1.57143;
    border-radius: 0.50rem;
    font-family: "Nunito Sans", sans-serif;

}

.btn_check {
      position: absolute;
      clip: rect(0,0,0,0);
      pointer-events: none;
    }

.extra-price-ingredient{
  position: absolute;
  top:-15px;
  right: -15px;
  background: #1dd800;
  color: white;
  width: 40px;
  height: 40px;
  z-index: 1000;
  display: grid;
  vertical-align: middle;
  align-content: center;
  text-align: center;
  border-radius: 50%;
}
.ingredient{
    border: 1px solid #03a9f3;
    color :#03a9f3;
    width: 85%;
    border-radius: 5px;
    padding: 4px;
    box-sizing: border-box;
    letter-spacing: 1px;
    margin: 8px 0;
  }
  .ingredient span:nth-child(even){
    padding-left: 10px;
    font-weight: bold;
  }
.btn-info {
    color: #fff;
    background-color: #03a9f3;
    border-color: #03a9f3;
}
  .btnextra{
   
    font-family: "Nunito Sans", sans-serif;
    cursor: pointer;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 0.9625rem 1.2em;
    font-size: 15px;
    line-height: 1.57143;
    border-radius: 0.5rem;

}
.myform_con
{
   width: 100%;
    padding: 1.3625rem 1.2em;
    font-size: 15px;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.30rem;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
 </style>

<style>
.link {padding: 10px 15px;background: transparent;border:#009c7c 1px solid;border-left:0px;cursor:pointer;color:#009c7c}
.disabled {cursor:not-allowed;color: #009c7c;}
.current {background: #bccfd8;}
.first{border-left:#009c7c 1px solid;}

#pagination{margin-top: 20px;padding-top: 30px;border-top: #009c7c 1px solid;font-size:20px;}
.dot {padding: 10px 15px;background: transparent;border-right: #009c7c 1px solid;}
#overlay {background-color: rgba(0, 0, 0, 0.6);z-index: 999;position: absolute;left: 0;top: 0;width: 100%;height: 100%;display: none;}
#overlay div {position:absolute;left:50%;top:50%;margin-top:-32px;margin-left:-32px;}
.page-content {padding: 20px;margin: 0 auto;}
.pagination-setting {padding:10px; margin:5px 0px 10px;border:#009c7c  1px solid;color:#009c7c;}
</style>
<!--<script src="http://code.jquery.com/jquery-2.1.1.js"></script> -->

<script>

  function getresult(url) {
    $.ajax({
        url: url,
        type: "GET",
        data:  {rowcount:$("#rowcount").val(),"pagination_setting":$("#pagination-setting").val()},
        beforeSend: function(){$("#overlay").show();},
        success: function(data){
        $("#pagination-result").html(data);
        setInterval(function() {$("#overlay").hide(); },500);
        },
        error: function() 
        {}          
   });
}
function changePagination(option) {
    if(option!= "") {
        getresult("getresult.php");
    }
}


 function changeqty(obj)
    {
        var p = (obj.id).slice(3);
		// alert(p);  
        var qty = document.getElementById("qty"+p).value;
		// alert(qty);
      //  var price = document.getElementById("price"+p).value;
        var rmk_value = document.getElementById("rmk_value"+p).value;
        var price_value = document.getElementById("price"+p).value;
         var subpro_price = document.getElementById("subpro_price"+p).value;
      // alert(price_value);
         if(subpro_price =="")
         {
            subpro_price =0;
         }
		   // alert(subpro_price);
		  var subtotal = parseFloat(price_value)+parseFloat(subpro_price);
		  // alert(subtotal);
        var subtotal = qty*subtotal;
        // var subtotal = (qty*rmk_value)+sub_pro;
        // alert(parseFloat(subtotal));
        document.getElementById("subtotal"+p).value = parseFloat(subtotal).toFixed(2);
       // $("subtotal"+p).val(subtotal.toFixed(2));

    var sst_rate = document.getElementById("sstvals").value;
    //alert(sst_rate);
    
         var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);

            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
              // alert(sst);
              sst_amt = parseFloat(sst)+parseFloat(subtol);

            });
             $("#subtol").text(subtol.toFixed(2));
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
             
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));


    }
 function display_keyboards() {

  $('.kb-text').keyboard({
    autoAccept: true,
    alwaysOpen: false,
    openOn: 'click',
    usePreview: false,
    layout: 'custom',
    //layout: 'qwerty',
    display: {
      'bksp': "\u2190",
      'accept': 'return',
      'default': 'ABC',
      'meta1': '123',
      'meta2': '#+='
    },
    customLayout: {
      'default': [
      '1 2 3 4 5 6 7 8 9 0 {bksp}',
     
      '{space} {cancel} {accept}'
      ],
      'shift': [
      'Q W E R T Y U I O P {bksp}',
      'A S D F G H J K L {enter}',
      '{s} Z X C V B N M / ? {s}',
      '{meta1} {space} {meta1} {accept}'
      ],
      'meta1': [
      '1 2 3 4 5 6 7 8 9 0 {bksp}',
      '- / : ; ( ) \u20ac & @ {enter}',
      '{meta2} . , ? ! \' " {meta2}',
      '{default} {space} {default} {accept}'
      ],
      'meta2': [
      '[ ] { } # % ^ * + = {bksp}',
      '_ \\ | &lt; &gt; $ \u00a3 \u00a5 {enter}',
      '{meta1} ~ . , ? ! \' " {meta1}',
      '{default} {space} {default} {accept}'
      ]}
    });
  $('.kb-text').keyboard({
    restrictInput: true,
    preventPaste: true,
    autoAccept: true,
    alwaysOpen: false,
    openOn: 'click',
    usePreview: false,
    layout: 'custom',
    display: {
      'b': '\u2190:Backspace',
    },
    customLayout: {
      'default': [
      '1 2 3 {b}',
      '4 5 6 . {clear}',
      '7 8 9 0 %',
      '{accept} {cancel}'
      ]
    }
  });

 }
function deleterow(obj)
{
   $("#"+obj.id).remove();
  // return false;
   $('#extra_venrt').val("");
  $('#extraprice_'+obj.id).val("");
  $('#extra_'+obj.id).val("");
  $('#remark_'+obj.id).val("");
  
      var sst_rate = document.getElementById("sstvals").value;
   var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);
			var s_count=0;
            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
              sst_amt = parseFloat(sst)+parseFloat(subtol);
			  s_count++;
            });
			// alert(s_count);
			if(s_count==0)
			{
				 $("#subtol").text(0);
             $("#sst").text(0);
             $("#my_sst").text(0);
              $("#sst_amt").text(0);
              $("#Payment").text(0);
			}
			else
			{
             $("#subtol").text(subtol.toFixed(2));
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));
			}

         return false;
}
 function getdata(obj)
 {

   var id = $(obj).attr("data-id");
   alert(id);
    // var id = obj.id;
    var ex_id = $('#extra_venrt').val();
    var rmk_val = $('#extraprice_'+id).val();
   // alert(rmk_val);
    var remark = $('#remark_'+id).val();
     var extravar = $('#extra_'+id).val();
     document.getElementById("pop_cart").style.backgroundColor="#03a9f3";
    $.ajax({
      type: 'post',
      url: 'productData.php',
      data: {p_id:id,sp_id:ex_id,remark:remark,extravar:extravar,rmk_val:rmk_val},
      success: function (data) {
      //alert(data);
      if( data != null ) {
     //  var flag = 0;
        /*
           $('tr.rd').each(function(){  
            var td1 = $(this).find('td').eq(0).text();
            var td2 = $(this).find('td').eq(1).text();
            var td3 = $(this).find('td').eq(2).text();
            var td4 = $(this).find('td').eq(3).text();
            var trid = $(this).closest('tr').attr('id');
            if(id == trid)
            {
             flag = 1;
            }
      });*/
      /* if (flag == 1) {
             var qtys = $("#qty"+id).val();
             var a = 1;
             var t_qty = parseInt(qtys)+ parseInt(a);
             var q_tol= $("#qty"+id).val(t_qty);
             var tol = $("#qty"+id).val();
             var price = $("#price"+id).val();

             //document.getElementById("remark_val"+id).value = $('#remark_'+id).val();
             $("#remark_val"+id).val($('#remark_'+id).val());

              $("#re"+id).text($('#remark_'+id).val());
            //  $("#re"+id).text($('#extra_'+id).val());

              document.getElementById("subtotal"+id).value = (tol*price).toFixed(2);
              $('#ProductModel').click();


          
       } else {*/
                //alert('The product added');
                $('#check'+id).html('<i class="fa fa-plus"></i>');
              $('#posData').append(data);
			    $('#posTable .qtyInput').each(function( index ) {
				  if($(this).hasClass('nmpd-target')){
					  
				  }
				  else{
					  $(this).numpad();
				  }
				});
            if($('#ProductModel').click())
            {
              document.getElementById("pop_cart").style.backgroundColor="#003a66";   
              // $('#remark_td').text("");
                //  $("#remark_td").html("");
                  //alert("hi");
              //document.getElementById('remark_td').innerHTML = '';
            }
                 $('#extra_venrt').val("");
                // $("#remark_td").html("");
                
                 
           //  }
  var sst_rate = document.getElementById("sstvals").value;
          var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);

            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
              sst_amt = parseFloat(sst)+parseFloat(subtol);

            });
             $("#subtol").text(subtol.toFixed(2));
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));

       }
      }
   });
 }
/*function Save_remark()
{
   var id= document.getElementById('remark_nm').value;
   if(id!="")
    {
      var product_id= document.getElementById('prd_id').value;
      $.ajax({
      type: 'post',
      url: 'productData.php',
      data: {p_id:product_id,remark: id},
      success: function (data) {
         if( data != null ) {
                var flag = 0;
                $('tr.rd').each(function(){  
                var trid = $(this).closest('tr').attr('id');
                if(product_id == trid)
                {
                 flag = 1;
                }
              });
             if (flag == 1) {
               $("#remark_val"+product_id).val(id);
               $('#my_modal').click();
              }else{
                    $('#posData').append(data);
                    $('#my_modal').click();
                 }

            }
          }
      });
  }else
  {
    alert("Remark Required Before Save.");
   }
}  */

function getSearch()
{
  var seach = document.getElementById("search2").value;

  $.ajax({
      type:'POST',
      url:'getresult2.php',
      data:{search:seach},
      success:function(data)
      {
        $("#searchData").html(data);
      }

  });
}


function getcust_name()
{
  var name = document.getElementById("txtname").value;
 // alert(name);
 if(name.length >= 10)
 {

   $.ajax({
      type:'GET',
      url:'getCustomer.php',
      data:{name:name},
      success:function(data)
      {
        //alert(data)
        $("#prod_id_data").html(data);
      }
 

  });

 }

}

 
</script>  
</head>
<body>
<div id="wrapper">
    <header id="header" class="navbar">
        <div class="container">
          
            <div class="col-xs-6"> 
              <div class = "col-xs-3"><a class="btn bblue" style="font-size: 32px;" title="Dashboard" data-placement="bottom" href="dashboard.php">
               <i class="fa fa-dashboard" style="color: #fff;"></i></a></div>

              <div class = "col-sm-3"><a class="navbar-brand" href="dashboard.php"><span class="logo"><span class="pos-logo-lg" style="line-height: 35px;">Koofamilies</span><span class="pos-logo-sm">POS</span></span></a></div>

              </div>
        
            <div class="header-nav">

                <ul class="nav navbar-nav pull-right">
                   <li class="dropdown">
                        <a class="btn bblue pos-tip" title="Orderlist" data-placement="bottom" href="orderview.php">
                           Orderlist
                        </a>
                    </li>

                   <!-- <li class="dropdown">
                        <a class="btn bblue pos-tip" title="Dashboard" data-placement="bottom" href="dashboard.php">
                            <i class="fa fa-dashboard"></i>
                        </a>
                    </li> -->
              
               </ul>

               <!-- <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn bblack" style="cursor: default;"><span id="display_time"></span></a>
                    </li>
                </ul> -->
            </div>
        </div>
    </header>


    <div id="content" class="col-lg-12">
        <div class="c1">
            <div class="pos">
                <div id="pos">
                    <form action="pos_payment.php" data-toggle="validator" role="form" id="pos-sale-form" method="post" accept-charset="utf-8">
					
                    <div id="leftdiv" class="col-xs-6 col-lg-12 ">
                        <div id="printhead">
                            <h4 style="text-transform:uppercase;">Koofamilies</h4>
                            <h5 style="text-transform:uppercase;">Order List</h5>
                        </div>
                      <!--  <div id="left-top">
                             <div class="no-print">
                                 <div class="form-group">
                                    <input type="text" class="form-control" name="txtname"placeholder=" Search By mobile Number" onkeyup="getcust_name()" id="txtname" value="">
                                 </div>
                  
                             </div>
                        </div> -->
                      
                        <div id="print">
                            <div id="left-middle" style="height: 300px; overflow: scroll;">
                              <!--  <div id="product-list">-->
                       <div>
                   
                          <div id="prod_id_data"></div>
                            <br>
                          <div style="clear:both;"></div>
                              <table class="table items table-striped table-bordered table-condensed table-hover" id="posTable" style="margin-bottom: 0px; padding: 0px;">
                                        <thead class="tableFloatingHeaderOriginal">
                                        <tr>
										     <th style="width: 5%; text-align: center;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
											 
                                            <th width="40%">Product</th>
											 <th width="10%">Qty</th>
											 <th width="15%">Remark</th>
                                            <th width="15%">Price</th>
                                            <th width="15%">Extra</th>
                                          
                                            <th width="20%">Subtotal</th>
                                           
                                            
                                          
                                          </tr>
                                          </thead>
                                           <tbody style="overflow-y:scroll; height:10px;" id="posData">
                                            </tbody>
                                       </table>
                                </div>
                            </div>

                             

                            <div style="clear:both;"></div>
                            <div id="left-bottom">
                                <table id="totalTable"
                                       style="width:100%; padding:5px; color:#000; background: #FFF;border: 1px solid red;">
                        
                                    <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Quantity : </td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="qty">0.00</span>
                                        </td>
                                    </tr>
                                   <!-- <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Total :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="">RM 10.00</span>
                                        </td>
                                    </tr>-->

                                     <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Net Total :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="subtol">RM 0.00</span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Service Charge :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="service_charge">RM 0.00</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            GST / SST ( <span id="my_sst"><?php echo $merchant_detail['sst_rate'];?></span> % ):</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="sst">RM 0.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #fff; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            Grand Total:</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #fff; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            <span id="sst_amt">RM 0.00</span>
                                        </td>
                                    </tr>

                                     <tr>
                                        <td class="show_cal" style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Payment :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="Payment" style="padding:7%;" class="show_cal btn btn-success btn-block">RM 0.00</span>
                                        </td>
                                    </tr>
                                     <tr>
                                  
                                      <td style="padding: 5px 10px; border-top: 1px solid #f0ad4e; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                            Table Number :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #51d2b7; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                           <div class="input-group">
                                   <!--   <textarea name="payment_note[]" id="payment_note_2" class="pa form-control kb-text" aria-haspopup="true" role="textbox"></textarea>-->
                                           <input class="form-control kb-text qtyInput" value="<?php if(isset($_GET['t'])){ echo $_GET['t'];} ?>" onclick="display_keyboards()" id="table" name="table" type="text" />
                                          </div>
                                        </td>
                                      </tr>
	
                                       <td style="padding: 5px 10px; border-top: 1px solid #f0ad4e; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                            Section :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #f0ad4e; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                           <div class="input-group" style="width:100%;">
                                      
                                           <!--<input class="form-control" value=""  id="Section" name="Section" type="text"/>-->
                                           <select class="form-control" name="Section" id="Section">
                                               <!--<option value="">Select Section </option> -->
                                               <?php 
                                               if(isset($_GET['s']))
											   {
												   $selected_section=$_GET['s'];
											   }
                                               $sql = mysqli_query($conn,"select * from sections where user_id ='".$_SESSION['mm_id']."'");
                                                while($data = mysqli_fetch_assoc($sql)){  ?>
												<option <?php if($data['name']==$selected_section){ echo "selected";} ?> value="<?php echo $data['id']; ?>"><?php  echo $data['name'];?></option>
                                                  
                                               <?php  }            
                                               ?>
                                               
                                           </select>
                                           
                                          </div>
                                        </td>
                                      </tr>

                                    
                                 <tr style="display:none;">
                                        <td style="padding: 5px 10px;border-top: 1px solid #DDD;">Sale Type</td>
                                        <td class="text-right" style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD;">
                                            <label class="radio-inline">
                                                <input type="radio" checked name="sell_type" value="0">Normal
                                            </label>                                       
                                       </td>
                                        
                                        <td class="text-right" style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD;">
                                            <label class="radio-inline">
                                                <input type="radio" name="sell_type" value="1">Extented
                                            </label>
                                         </td>
                                    </tr> 
                                    
                                    
                                    
                                    
                                   <!-- <tr>
                                        <td style="padding: 5px 10px;">Order Tax                                            <a href="#" id="pptax2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-right" style="padding: 5px 10px;font-size: 14px; font-weight:bold;">
                                            <span id="ttax2">0.00</span>
                                        </td>
                                        <td style="padding: 5px 10px;">Discount                                                                                        <a href="#" id="ppdiscount">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                                                                    </td>
                                        <td class="text-right" style="padding: 5px 10px;font-weight:bold;">
                                            <span id="tds">0.00</span>
                                        </td>
                                    </tr> -->
                                   <!-- <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            Total Payable                                        </td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            <span id="gtotal">0.00</span>
                                        </td>
                                    </tr>-->
                                </table>

                                <div class="clearfix"></div>
                                <div id="botbuttons" class="col-xs-12 text-center">
                                    <input type="hidden" name="biller" id="biller" value="2035"/>
                                    <input type="hidden" name="status" id="status" value="0"/>
                                    <input type="hidden" name="discount_amount" id="discount_amount" value="0"/>
                                    <input type="hidden" name="paid_amount_pos" id="paid_amount_pos" value="0"/>
                                    <input type="hidden" name="change_pos" id="change_pos" value="0"/>
                                    <div class="row">
                                        
                                        <div class="col-xs-6" style="padding: 0;">
                                             <button type="reset" class="btn btn-danger btn-block btn-flat" id="reset" style="height:67px;">Clear All</button>
                                        </div>
                                        <div class="col-xs-6" style="padding: 0;">
                                            <button type="submit" class="btn btn-success btn-block pos_submit" id="" style="height:67px;">
                                                <i class="fa fa-money" style="margin-right: 5px;"></i>Submit </button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                    </form> 
                    <?php
                    //vandhu

               $id = $_SESSION['mm_id'];
      
                    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
                    //FETCH PRODUCT BY USING MERCHAT ID Amit
                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0"));

                                $total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0 ");
                     
                    $favorite = isset($_SESSION['login']) ? mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."") : '';
                    $count = $favorite != '' ? mysqli_num_rows($favorite) : 0;
                    ?>
					<style>
						.calculator.jin_calculator {
							width: 20em;
							padding-top: 1em;
						}

						.button.jin_button {
							width: 4em;
							height: 4em;
							margin-top: 0;
							margin-bottom: 1em;
						}

						.viewer.jin_viewer {
							width: 13.5em;
							height: 4em;
							margin-top: 0;
							margin-bottom: 1em;
							line-height: 4em;
						}
					</style>
					 <div class="fixside" id="fixside" style="display:none;">
					 
					    <div class="modal-content" style="height:430px !important">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel"> Processing</h5>
                <button type="button" class="close" id="pending_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="t" rue="">×</span></button>
            </div>
            
           
			<div class="row">
			  <div class="col-md-6 input-has-value" style="margin-left:1%;">
			  
							<div style="padding-top: 5px;display: flex;font-weight:bold;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty3" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty3" id="tol_qty3" value="">
                                                <span id="total_amount3" style="font-size:20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt3" id="tol_mnt1" value="">
                                            </div>

                                            <div style="padding-top: 5px;font-weight:bold;display: flex;" class="input-has-value">
                                                <span style="font-size:20px; width: 40%;    border: 1px solid; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid</span>
                                                <input type="text" id="paid3" value="0" class="amount" name="paid" style=" font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											 <div style="padding-top: 5px;font-weight:bold;display: flex;" class="input-has-value">
                                                <span style="font-size:20px; width: 40%;    border: 1px solid; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Discount</span>
                                                <input type="text" id="discount3" value="0" class="amount" name="discount" style=" font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											
                                            <div style="padding-top: 5px;font-weight:bold;display: flex;">
                                                <span style="font-size:20px; width: 40%;    border: 1px solid;border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Change</span>
                                                <input type="text" id="change3" name="change" class="amount" style=" font-size: 20px; width: 37%;margin-left: 23%; border: 1px solid #555555;padding-left: 4px;">
                                            </div>
											<br>
											<input type="submit" class="btn btn-success btn-block waves-effect waves-light pay_submit" style="background-color:#337ab7;height:60px;" value="Submit">
                                         
											 
			  </div>
			  <div class="col-md-5">
			    <div class="" style="float: left;">

                                                <form id="cal1" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <tr><th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">Calculator</th>
                                               
                                                </tr></thead>
                                               
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                               <div id="calculator3" class="calculator input-has-value jin_calculator">
													<input type="button" id="clear3" class="clear button jin_button" value="C">

													<div id="viewer3" class="viewer jin_viewer">0</div>

													<input type="button" class=" num3 button jin_button" data-num="7" value="7" id="7">
													<input type="button" class="num3 button jin_button" data-num="8" value="8" id="8">
													<input type="button" class="num3 button jin_button" data-num="9" value="9" id="9">
													<input type="button" data-ops="plus" class="ops3 button jin_button" value="+">

													<input type="button" class=" num3 button jin_button" data-num="4" value="4" id="4">
													<input type="button" class="num3 button jin_button" data-num="5" value="5" id="5">
													<input type="button" class="num3 button jin_button" data-num="6" value="6" id="6">
													<input type="button" data-ops="minus" class="ops3 button jin_button" value="-">

													<input type="button" class="num3 button jin_button" data-num="1" value="1" id="1">
													<input type="button" class="num3 button jin_button" data-num="2" value="2" id="2">
													<input type="button" class="num3 button jin_button" data-num="3" value="3" id="3">
													<input type="button" data-ops="times" class="ops3 button jin_button" value="*">

													<input type="button" class="num3 button jin_button" data-num="0" value="0" id="0">
													<input type="button" class="num3 button jin_button" data-num="." value="." id=".">
													<input type="button" id="equals3" class="equals3 button jin_button" data-result="" value="=">
													<input type="button" data-ops="divided by" class="ops3 button jin_button" value="/">
												</div>
											
											<table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            
                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    
                                        </form>
                            
                                        </div>
			  </div>
			</div>
			 
              
		
    </div>
	
					 </div>
					 <style type="text/css">
				   .fixside
				   {
						position: fixed;
						bottom: 0;
						right:2%;
						top: 30%;
						width: auto;
						max-width: 800px;
						height: 600px;
						border: 5px solid #73AD21;
						z-index: 999;
						opacity: 1;
						background: white;
						font-size: 26px;
					}
					#qty_panel 
					{
						padding: 0 0 15px;
						text-align: center;
						display: block;
						vertical-align: middle;
						background-color: #51d2b7;
					}
					.qty_value {
							border: 2px solid transparent;
							width: 80px;
							height: 80px;
							display: inline-block;
							margin: 15px 0 0 15px;
							cursor: pointer;
							background-color: #009c7c;
							font-size: 25px;
							padding-top: 20px;
						}
					</style>
					<script>
						function calcTotalAmount() {
							var sst_rate = document.getElementById("sstvals").value;
							var qd=0;
							var subtol = 0;
							var sst =0;
							$(".qd").each(function() {
								qd = parseInt(qd)+parseInt($(this).val());
							});

							$("#qty").text(qd);

							$(".subt").each(function(){
								subtol = parseFloat(subtol)+parseFloat($(this).val());
								sst = subtol * sst_rate/100;
								sst_amt = parseFloat(sst)+parseFloat(subtol);
							});
							
							$("#subtol").text(subtol.toFixed(2));
							// alert(sst.toFixed(2));
							$("#sst").text(sst.toFixed(2));
							$("#my_sst").text(sst_rate);
							$("#sst_amt").text(sst_amt.toFixed(2));
							$("#Payment").text(sst_amt.toFixed(2));
						}

						$(document).on("click",'.qty_value', function(e) { 
							e.preventDefault();

							if ($('#posData>.pos_data').length == 0) {
								return;
							}
							
							var tr = $('#posData>.pos_data:last');

							var qty = parseFloat($(this).attr('qty'));
							var price = parseFloat(tr.find('.jin_price').val());
							var extra_price = parseFloat(tr.find('.jin_extra_price').val());
							console.log(qty);
							console.log(price);
							console.log(extra_price);
							var amount = qty * (price + extra_price);
							
							console.log(amount);

							tr.find('.qd').val(qty);
							tr.find('.subt').val(amount.toFixed(2));

							calcTotalAmount();
							initializeNumpad();
						});
					</script>
                    <div id="cp">
                        <div id="cpinner" style="background-color: #51d2b7;"  class="col-lg-12 col-xs-6">
                            <div class="quick-menu">
                                <div id="proContainer">
                                    <div id="ajaxproducts">
                                        <div class="col-sm-12  text-center" style="padding:0px;">
										<div class="col-sm-10 text-center" >
												<div id="qty_panel" class="col-md-12">
													<div class="qty_value" qty="2">×2</div>
													<div class="qty_value" qty="3">×3</div>
													<div class="qty_value" qty="4">×4</div>
													<div class="qty_value" qty="5">×5</div>
												</div>
											</div>
                                        
                                        <input type="hidden" name="sstvals" id="sstvals" value="<?php echo $merchant_detail['sst_rate'];?>">
                                           <!--div class="col-sm-6 text-center example" >
                                           <input type="text" placeholder="Search.." name="search2" id="search2">
                                            <button type="button" onclick="getSearch()"><i class="fa fa-search"></i></button>
                                          
                                           </div!-->
                                               <?php if($pos_type==2)include('getresult.php'); else include('getresult2.php'); ?>


                                        <div class="btn-group btn-group-justified pos-grid-nav" style="left:-40px;">
										   <input type="hidden" name='varient_must' id='varient_must'/>
        <input type="hidden" name='varient_count' value='0' id='varient_count'/>
        <input type="hidden" name='extra_value' value='0' id='extra_value'/>
                                           <div class="col-sm-12 text-center">  <div id="overlay"><div><img src="loading.gif" width="64px" height="64px"/></div></div>
                                             <div id="pagination-result">
                                               <input type="hidden" name="rowcount" id="rowcount" />
                                              </div> 
                                            </div>

                                        </div>

                                       <!-- <div class="btn-group btn-group-justified pos-grid-nav">
                                            <div class="btn-group">
                                                <button style="z-index:10002;" class="btn btn-primary pos-tip" title="Previous" type="button" id="previous">
                                                
                                                </button>
                                            </div>
                                                                        
                                             <div class="btn-group">
                                                <button style="z-index:10004;" class="btn btn-primary pos-tip" title="Next" type="button" id="next">
                                                    <i class="fa fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div> -->
                                        <div style="clear:both;"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>
<div class="rotate btn-cat-con">
    <button type="button" id="open-subcategory" class="btn btn-warning open-subcategory"><i class="fa fa-caret-left"></i></button>
    <button type="button" id="open-category" class="btn btn-primary open-category"><i class="fa fa-caret-right"></i></button>
</div>

<!-- Modal -->

<!--<div class="modal" id="my_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Remark</h4>
      </div>
      <div class="modal-body">
        <div id="remark_data"></div>
        <input type="text" class="form-control" placeholder="Remark" name="remark_nm" id="remark_nm" value=""/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="" onclick="Save_remark()">Save & Add</button>
      </div>
    </div>
  </div>
</div> -->

<!--test modal for remark--->

    <div id="remarks_area" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                      <h4 class="modal-title">Remarks</h4>
                  </div>
                  <div class="modal-body">
                    <?php 
                      $ingredients = json_decode(mysqli_fetch_row(mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'"))[0]);
                      foreach ($ingredients as $ingredient) {
                        if(!empty($ingredient)){
                          // $ingName = (sizeof(explode("[", $ingredient)) > 1) ? explode("[", $ingredient)[0] : $ingredient;
                          $ingName = $ingredient->name;
                          $ingPrice = ($ingredient->price == 0) ? null : $ingredient->price;
                          // $ingPrice = (!empty(explode("]",explode("[", $ingredient)[1])[0])) ? explode("]",explode("[", $ingredient)[1])[0] : null;

                          ?>
                           <div style="margin: 10px 8px;" class="btn-group" data-toggle="buttons" data-subcategory='<?php echo $ingredient->subcategory; ?>'>
                              <label class="btn btn-secondary">
                                <input type="checkbox" name="ingredient" value="<?php echo $ingName; ?>" autocomplete="off"> 
                                <?php
                                    echo ucfirst(str_replace("_", " ",$ingName)); 
                                ?>
                              </label>
                              <?php  if(!is_null($ingPrice)){ ?><div class='extra-price-ingredient'><?php echo number_format($ingPrice,2,".",""); ?></div><?php } ?>
                            </div>
                            <?php
                        }
                      }

                    ?>
                    <input type="hidden" name="remark" id="remark_input" class="form-control" style="margin: 10px 0" placeholder="Write here your remarks"/>
                    <div id="small_text" style="position:relative;margin-top:2em;width:100%;">
                      <small style="position: absolute;bottom: 2px;left: 5px;font-weight: bold;color:red;">Note : e.g. 0.30 = Rm 0.30</small>
                    </div>
                  </div>
                  <div class="modal-footer" style="position: relative;padding:40px 0 25px 0">
                    <div style="position: absolute;left:5px;text-align: left;top:5px;display:none;">
                        Quantity: <input type="hidden" id="quantity_input" name="quantity_input" value="1" style="width:2em;text-align:center;"/>
                    </div>
                    <button type="button" id="reset_remark" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success save_close" data-dismiss="modal">
                    Save and
                    <p class="text_add_cart" style="width: 20px; height: 20px; font-size: 12px;padding: 4px 0 0 0;">
                      <i class="fa fa-plus"></i>
                    </p> 
                    </button>
          <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>
                    <button type="button" class="btn btn-default manual_input">Manual input</button>
          <?php } ?>
                  </div>
              </div>
          </div>
        </div>
      

       <div class="modal fade" id="ProductModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Product Varieties For <br/> <span id="varient_name" style="font-weight:bold;"></span></h4>
                    
                    <p id="varient" style="display:none;"></p>
                    <p id="varient_type" style="display:none;"></p>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body product_data" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">
                     <p id="varient_error" style="color:red;display:none;">Please select at least one choice. Thank</p>
                      
                       <div id="product_main" class="ingredients_container">
                         
                       </div>
                      
                      <div class="product_extra">
                      <input id="p_pop_price"  type="hidden"/>
                      <table border="1px solid" style="width:80%;color:black;">
                      <tr><td> Product Name </td><td> Rm </td></tr>
                       <tbody id="product_table">
                          
                       </tbody>
                        
                        <tr><td> <b> Total : </b></td><td id="pr_total"></td></tr>
                        <tbody><tr><td>Remarks</td><td id="remark_td"></td></tr></tbody>
                      </table>
                       <br/>
                      
                        <!--p id="pr_total"></p!-->
                        
                      </div>
                      
                    
                                        </div>
                                        <div style="margin: 10px 0;"  class="modal-footer product_button pop_model">
                        
                                         
                                        </div>
                        <br/>
                                    </form>
                                </div>
                            </div>
                        </div>

 <div class="modal fade" id="myScanModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content" style="width: 100%; min-height: 500px;max-height: 600px;border-radius: 4px;background-color: transparent;    padding: 0px;">
                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color: #99e1dc57;margin: 0px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="color: #3a3939c4;">Statement</h4>
                                    </div>
                                    <div style="height: 550px;background-color: #99e1dc;padding: 10px;">
                                        <div class="inline fields">
                                            <div style="display: inline-block;height: 50px;">
                                                <label style="display: inline-block;height: 50px;">Barcode</label>
                                                <input type="text" id="barcode" autofocus style="display: inline-block;height: 50px;">
                                            </div>
                                            <div style="display: inline-block;height: 50px;">
                                                <button style="width: 100px; height: 50px;background-color: #99e1dc;" id="add_invoice">Add</button>
                                            </div>
                                        </div>
                                        <form id="scan" style="height: 476px; padding-top: 10px;">
                                            <table style="width: 100%;">
                                                <thead>
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">No</th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">InvoiceNumber</th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">Qty</th>
                                                <th style="padding-left: 5px;">Amount</th>
                                                </thead>
                                                </thead>
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;height: 357px;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;" id="scanned_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                <span id="total_amount" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                            </div>
                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">
                                                <button style="width:200px;height:50px;background-color: #99e1dc;">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
						 <div class=" modal fade" id="ProductAdded" role="dialog">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p>The product added</p>
                   <div id="without_varient_footer" class="modal-footer model_pop" style="padding-bottom:2px;">
                   <input type="hidden" id="pop_ok" name="pop_ok">
                   <input type="hidden" id="last_add_id" name="last_add_id" value='1'>
                    <!--button role="button" style="min-height:40px;position:static !important;" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal" data-target="#remarks_area" disabled="">Remarks</button!-->
                    <button role="button" class="close_pop btn btn-large btn-primary" style="background:#50D2B7;border:none;">Ok</button>
                  </div>    
                                </div>
                            </div>
    </div>

</body>
</html>

<style type="text/css">
    .parent-category-menu {
        background-color: #fff;
        padding-top: 6px;
        padding-bottom: 6px;
        -webkit-box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        position: relative;
    }
    .parent-category-menu a {
        padding: 8px 18px 8px 18px;
        display: inline-block;
        vertical-align: top;
        line-height: normal;
        font-size: 14px;
        color: #4a5368;
        font-weight: 600;
        background-color: transparent;
        border-left: 0.5px solid black;
        box-shadow: none;
		font-size:30px;
    }
    .merchant-layout-2 .sub_category_grid{
        background: #e9ebf1;
        margin-top: 0;
    }
    .merchant-layout-2 .sub_category_grid .category_filter{
        margin-right: 0px;
        width: 100%;
        border-bottom: 1px solid black;
    }
    .merchant-layout-2 .sub_category_grid button{
        width: 100%;
        display: block;
        background-color: transparent;
        border: 0;
        color: #4a5368;
        border-radius: 0px;
        box-shadow: none;
        white-space: normal;
        text-align: left;
		font-size:35px;
		
		
    }
    .merchant-layout-2 .text_add_cart, .modal-footer .text_add_cart{
        background-color: #50d2b7;
        width: 30px;
        height: 30px;
        font-size: 16px;
        border-radius: 100%;
        text-align: center;
        line-height: normal;
        padding: 6px 0 0 0;
        margin: 0;
        display: inline-block;
        vertical-align: top;
    }
    .merchant-layout-2 .common_quant{
        display: block;
        text-align: right;
    }
    .merchant-layout-2 .grid .grid-item{
        background-color: #ffffff;
        padding: 15px;
        -webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        margin-bottom: 15px;
        width: 100%;
    }
    .element-item .introduce-remarks{
        font-size: .8em;
        position: absolute;
        z-index: 10;
        bottom: 0;
        top: 33px;
        right: 6vw;
        width: 5em;
        height: 30px;
        border-radius: 10px;
        box-sizing: border-box;
        padding: 0;
        display: grid;
        align-content: center;
    }
    @media (max-width: 767px) {
        .product_name_field{
            min-height: 3em;
        }
        .parent-category-menu a{
            padding: 8px 12px 8px 12px;
            width: 24%;
        }
        .main-wrapper {
            padding: 0 0 0 15px;
        }
        .merchant-layout-2 .sub_category_grid button {
            font-size: 12px;
        }
        .merchant-layout-2 .sub_category_grid .category_filter {
            padding: 6px 4px;
        }
        .merchant-layout-2 .grid .grid-item{
            padding: 8px;
        }
        .element-item .introduce-remarks{
            position: absolute;
            font-size: .8em;
            bottom: 5px;
            right: 35px;
            margin: 0;
            top: auto;
        }
        .element-item .row .col-12:nth-child(1) .introduce-remarks{
            right: 35px;
            bottom: 5px;
            left: auto;
        }
    }
    @media (max-width: 480px) and (min-width: 315px){
        .wrapper{
            width: 100%;
        }
    }
</style>
<style type="text/css">
 

/* Gradient text only on Webkit */
.warning {
  background: -webkit-linear-gradient(45deg,  #c97874 10%, #463042 90%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  color: #8c5059;
  font-weight: 400;
  margin: 0 auto 6em;
  max-width: 9em;
}

.calculator {
  font-size: 18px;
  margin: 0 auto;
  width: 10em;
  &::before,
  &::after {
    content: " ";
    display: table;
  }
  
  &::after {
    clear: both;
  }
}

/* Calculator after dividing by zero */
.broken {
  animation: broken 2s;
  transform: translate3d(0,-2000px,0);
  opacity: 0;
}

.viewer {
  color: #c97874;
  float: left;
  line-height: 3em;
  text-align: right;
  text-overflow: ellipsis;
  overflow: hidden;
  width: 7.5em;
  height: 3em;
}

.button {
  border: 0;
  background: #99e1dc;
  color: #000;
  cursor: pointer;
  float: left;
  font: inherit;
  margin: 0.20em;
  width: 2em;
  height: 2em;
  transition: all 0.5s;
  
  &:hover {
    background: #201e40;
  }
  
  &:focus {
    outline: 0; // Better check accessibility

    /* The value fade-ins that appear */
    &::after {
      animation: zoom 1s;
      animation-iteration-count: 1;
      animation-fill-mode: both; // Fix Firefox from firing animations only once
      content: attr(data-num);
      cursor: default;
      font-size: 100px;
      position: absolute;
           top: 1.5em;
           left: 50%;
      text-align: center;
      margin-left: -24px;
      opacity: 0;
      width: 48px;    
    }
  }
}

/* Same as above, modified for operators */
.ops:focus::after {
  content: attr(data-ops);
  margin-left: -210px;
  width: 420px;
}

/* Same as above, modified for result */
.equals:focus::after {
  content: attr(data-result);
  margin-left: -300px;
  width: 600px;
}

/* Reset button */

.reset {
  background: rgba(201,120,116,.28);
  color:#c97874;
  font-weight: 400;
  margin-left: -77px;
  padding: 0.5em 1em;
  position: absolute;
    top: -20em;
    left: 50%;
  width: auto;
  height: auto;
  
  &:hover {
    background: #c97874;
    color: #100a1c;    
  }
  
  /* When button is revealed */
  &.show {
    top: 20em;
    animation: fadein 4s;
  }
}

/* Animations */

/* Values that appear onclick */
@keyframes zoom {
  0% { 
    transform: scale(.2); 
    opacity: 1;
  }
  
  70% { 
    transform: scale(1); 
  }
  
  100% { 
    opacity: 0;
  }
}

/* Division by zero animation */
@keyframes broken {
  0% {
    transform: translate3d(0,0,0);
    opacity: 1;
  }

  5% {
    transform: rotate(5deg);
  }

  15% {
    transform: rotate(-5deg);
  }

  20% {
    transform: rotate(5deg);
  }

  25% {
    transform: rotate(-5deg);
  }

  50% {
    transform: rotate(45deg);
  }

  70% {
    transform: translate3d(0,2000px,0);
    opacity: 1;
  }

  75% {
    opacity: 0;
  }

  100% {
    transform: translate3d(0,-2000px,0);
  }
}

/* Reset button fadein */
@keyframes fadein {
  0% {
    top: 20em;
    opacity: 0;
  }
  
  50% {
    opacity: 0;
  }
  
  100% {
    opacity: 1;
  }
}

@media (min-width: 420px) {
  .calculator {
    width: 12em;
  }
  .viewer {
    width: 8.5em;
  }
  .button {
    margin: 0.5em;
  }
}

@media (max-width: @screen-xs-min) {
  .modal-xs { width: @modal-sm; }
}

.modal-lg {
  max-width: 900px;}
@media (min-width: 768px) {
   .modal-lg {
    width: 100%;
  } 
}
@media (min-width: 992px) {
  .modal-lg {
    width: 900px;
  }
}

.blinking{
    animation:blinkingText 1.5s infinite;
	color:red;
	
}
@keyframes blinkingText{
    0%{     color: red;    }
    49%{    color: transparent; }
    50%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color:red;    }
}
    </style>

<script type="text/javascript">
// calcuator 3 

(function() {
  "use strict";

  // Shortcut to get elements
  var el = function(element) {
    if (element.charAt(0) === "#") { // If passed an ID...
      return document.querySelector(element); // ... returns single element
    }

    return document.querySelectorAll(element); // Otherwise, returns a nodelist
  };

  // Variables
  var viewer = el("#viewer3"), // Calculator screen where result is displayed
    equals = el("#equals3"), // Equal button
    nums = el(".num3"), // List of numbers
    ops = el(".ops3"), // List of operators
    theNum = "", // Current number
    oldNum = "", // First number
    resultNum, // Result
    operator; // Batman

  // When: Number is clicked. Get the current number selected
  var setNum = function() {
    if (resultNum) { // If a result was displayed, reset number
      theNum = this.getAttribute("data-num");
      resultNum = "";
    } else { // Otherwise, add digit to previous number (this is a string!)
      theNum += this.getAttribute("data-num");
    }

    viewer.innerHTML = theNum; // Display current number

  };

  // When: Operator is clicked. Pass number to oldNum and save operator
  var moveNum = function() {
    oldNum = theNum;
    theNum = "";
    operator = this.getAttribute("data-ops");

    equals.setAttribute("data-result", ""); // Reset result in attr
  };

  // When: Equals is clicked. Calculate result
  var displayNum = function() {

    // Convert string input to numbers
    oldNum = parseFloat(oldNum);
    theNum = parseFloat(theNum);

    // Perform operation
    switch (operator) {
      case "plus":
        resultNum = oldNum + theNum;
        break;

      case "minus":
        resultNum = oldNum - theNum;
        break;

      case "times":
        resultNum = oldNum * theNum;
        break;

      case "divided by":
        resultNum = oldNum / theNum;
        break;

        // If equal is pressed without an operator, keep number and continue
      default:
        resultNum = theNum;
    }

    // If NaN or Infinity returned
    if (!isFinite(resultNum)) {
      if (isNaN(resultNum)) { // If result is not a number; set off by, eg, double-clicking operators
        resultNum = "You broke it!";
      } else { // If result is infinity, set off by dividing by zero
        resultNum = "Look at what you've done";
        el('#calculator3').classList.add("broken"); // Break calculator
        el('#reset3').classList.add("show"); // And show reset button
      }
    }

    // Display result, finally!
    viewer.innerHTML = resultNum;
	// alert(resultNum);
	$('#paid3').val(resultNum);
    equals.setAttribute("data-result", resultNum);

    // Now reset oldNum & keep result
    oldNum = 0;
    theNum = resultNum;

  };

  // When: Clear button is pressed. Clear everything
  var clearAll = function() {
    oldNum = "";
    theNum = "";
    viewer.innerHTML = "0";
    equals.setAttribute("data-result", resultNum);
  };

  /* The click events */

  // Add click event to numbers
  for (var i = 0, l = nums.length; i < l; i++) {
    nums[i].onclick = setNum;
  }

  // Add click event to operators
  for (var i = 0, l = ops.length; i < l; i++) {
    ops[i].onclick = moveNum;
  }

  // Add click event to equal sign
  equals.onclick = displayNum;

  // Add click event to clear button
  el("#clear3").onclick = clearAll;
  

}());
</script>
<script type="text/javascript">
   function initializeNumpad() {
	   $( ".qtyInput" ).each(function() {
		   if(!$(this).hasClass('nmpd-target')){
			   $(this).numpad({displayTpl: '<input class="form-control" type="number" />'});
		   }
	   });
   }
  $( document ).ready(function() {
	 			 initializeNumpad(); // Instantiate NumPad once the page is ready to be shown
				$('.qtyInput').on('change blur',function(){
					
					if($(this).val().trim().length === 0){
						$(this).val(1);
					}
				});
				$('#posTable .qtyInput').numpad();
				$('#posTable tr').on('click', function(e){
					
					$(this).find('.qtyInput').numpad('open');
				});
	var showModal = true;
	// init Isotope
	var $grid_sub = $('.sub_category_grid').isotope({
		// options
		layoutMode: 'fitRows'
	});
	var $grid = $('.grid').isotope({
	  // options
	});
	$(document).on("click",'.show_cal', function(e) { 
	    e.preventDefault();
		var total_q=$('#qty').html();
		var Payment=$('#Payment').html();
		// alert(total_q);
		$('#total_qty3').html(total_q);
		$('#total_amount3').html(Payment);
		$('.fixside').show();
	});
	$("#pending_close").click(function() {
			$('.fixside').hide();
		});
		$('.pos_submit').click(function() {
		 $("input.pay_submit").prop("disabled",false);
		 	
	});
	$('.pay_submit').click(function() {
		 $("input.pay_submit").prop("disabled",false);
		 $('#status').val(1);  
      document.getElementById("pos-sale-form").submit();		
	});
	$('.num3').click(function() { 
                    // alert(2);
                       var mb = $('#viewer3').text();
					  // alert(mb);
					  

                     $('#paid3').val(mb);
                      var paid3=$('#paid3').val();
                     var tol = document.getElementById("total_amount3").innerText;
					 // alert(paid3);
				    $('#paid_amount_pos').val(paid3);
                      var final = parseFloat(tol)-parseFloat(mb);
                      var value = Math.abs(final);
                      var v = value.toFixed(2);
					  // alert(v);
                      $('#change_pos').val(v);
                      $('#change3').val(v);
                      
                       var tol_qty = document.getElementById("total_qty3").innerText;
                      // alert(tol_qty);
                    
                        $('#total_qty3').val(tol_qty);
                        $('#total_amount3').val(tol);
						


                    });
	 $("#discount3").on("keyup", function(){
		var dis_num = $(this).val();
		if(!dis_num)
			dis_num=0;
		var tol = document.getElementById("total_amount3").innerText;
		var paid3=$('#paid3').val();
		// var total_paid = parseFloat(dis_num) + parseFloat(paid3);
		var mb = $('#viewer3').text();
		// alert(paid3);
		// var final = parseFloat(tol) - parseFloat(total_paid);
		var final = parseFloat(paid3) - parseFloat(tol) - parseFloat(dis_num);
		var value = Math.abs(final);
		var v = value.toFixed(2);
		$('#change3').val(v);
		$('#paid_abount_pos').val(paid3);
		$('#change_pos').val(v);
		$('#discount_amount').val(dis_num);

	 });  
 
	 $("#paid3").on('keyup', function(){
		 
		 var paid3 = $(this).val();
		 var tol = document.getElementById('total_amount3').innerText;
		 var dis_num = $('#discount_amount').val();
		 if(!dis_num)
			dis_num=0;
		 // var total_paid = parseFloat(dis_num) + parseFloat(paid3);
		 var mb = $('#viewer3').text();
		 // var final = parseFloat(tol) - parseFloat(total_paid);
		 console.log(paid3 + ':' + tol + ':' + dis_num);
		 var final = parseFloat(paid3) - parseFloat(tol) - parseFloat(dis_num);
		 var value = Math.abs(final);
		 var v = value.toFixed(2);
		 $('#change3').val(v);
		 $('#paid_amount_pos').val(paid3);
		 $('#change_pos').val(v);
		 $('#discount_amount').val(dis_num);
	 });
		
	  
	$(document).on("click",'.master_category_filter', function(e) { 
	    e.preventDefault();
		var filterValue = $(this).attr('data-filter');
		// alert(filterValue);
		  $grid_sub.on( 'arrangeComplete', function ( event, filteredItems) {
        console.log(event, filteredItems);
        $(filteredItems[0].element).find('button').trigger('click');
        console.log('am called');
		});

		$grid_sub.isotope({ filter: filterValue });
	});
	$(document).on("click",'.sub_category_grid .category_filter button', function(e) {
		var filterValue = $(this).attr('data-filter');
		var subcateg_show = $(this).data("subcategory");
		console.log(filterValue);
		console.log(subcateg_show);
		$("#remarks_area .modal-body .btn-group").each(function(){
        if($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all"){
          $(this).show();
        }else{
          $(this).hide();
        }
      });
      $grid.isotope({ filter: filterValue });
	});
	 $('.master_category_filter:first-child').trigger('click');
    $("#reset").click(function(){
		$(".pos_data").remove();
		$('#qty').html('0.00');
		$('#subtol').html('0.00');
		$('#service_charge').html('0.00');
		$('#sst').html('0.00');
		$('#sst_amt').html('0.00');
		$('#Payment').html('0.00');
	
	});
	  $("#reset_remark").click(function(){
      $("#remarks_area .btn.btn-secondary.checkbox-checked.active").removeClass("checkbox-checked").removeClass("active");
    });
   $("#remarks_area").on("shown.bs.modal", function(){
      $("body").addClass("noscroll");
    });
    $('#remarks_area').on('hide.bs.modal', function (e) {
		
        $(this).removeClass("transaction");
     $("body").removeClass("noscroll");
        $("#remark_input").attr("type","hidden").val('');
        $("input[name='ingredients'].selected").removeClass("selected");
        $("a.introduce-remarks.selected").removeClass("selected");
    });
    $("body").on("click",".introduce-remarks", function(e){
      e.preventDefault();
      $(this).addClass("selected");
	  var data_id=$(this).attr('data_id');
	  // alert(data_id);
	   $('#remarks_area').attr("data_id",data_id)
      if($(this).parent().parent().parent().parent().hasClass("element-item")){
        var id = $(this).siblings("input[name='p_id']").val();
		// alert(id);     
        $("#remarks_area").addClass("transaction").attr("data-id", id);
      }
      if($(this).parent().is("td")){
		  var quantity_str =  $(this).closest('td').closest('td').attr('id');
		  var quantity_input_id=quantity_str.substring(2, quantity_str.length);
		  // alert(quantity);
        $("#remarks_area").find("input[name='quantity_input']").parent().hide();
      }else{
        // $("#remarks_area").find("input[name='quantity_input']").parent().show();
      }
	  // var re=$(this).closest('td').find().val();
      
	  // alert(re);
      // quantity = (quantity == null || quantity == undefined) ? $(this).siblings("#pop_cart").data("quantity") : quantity;
	  var q_ids='#qty'+quantity_input_id;
	  // alert(q_ids);
	  var quantity=$(q_ids).val();
	  // alert(quantity);
	  $('#quantity_input').val(quantity);
      $("#ProductAdded").modal("hide");
      $(this).parent().parent().find("input[name='ingredients']").addClass("selected");
      $(this).parent().parent().find("input[name='single_ingredients']").addClass("selected");
      var ingredients = $(this).parent().parent().find("input[name='ingredients']").val();
      $("#remarks_area .modal-body .btn-group .btn-secondary").removeClass("checkbox-checked").removeClass("active");
      console.log(quantity);
      // $("#remarks_area .modal-footer input[name='quantity_input']").val(quantity);
    });
    $('#remarks_area').on('click','.save_close', function (e) {
		// alert(4);
		 e.preventDefault();
        var selected = [];
        var extras = [];
		
		var data_id=$('#remarks_area').attr('data_id');
        var quantity = $(this).parent().find("input[name='quantity_input']").val();
		// alert(data_id);
        // var quantity =1;
	
        $('div#remarks_area .btn-secondary.active').each(function() {
			// alert(2);
            selected.push($(this).children("input[name='ingredient']").val());
            val_extra = $.trim($(this).siblings(".extra-price-ingredient").html());
			// alert(val_extra);
            if(val_extra != ''){
              extras.push(val_extra);
            }
        });
		
        console.log(extras);
        if($("#remark_input").val() != ''){
          selected.push($("#remark_input").val().split(' ').join('_'));
        }
        var input_extras = 0;
		// var s_id="subpro_price"+data_id;
		// alert(s_id);
		 var subpro_price = document.getElementById("subpro_price"+data_id).value;
		 var sub_value = document.getElementById("sub"+data_id).value;
		 // alert(subpro_price);
        for (var i = 0; i < extras.length; i++) {
          input_extras += parseFloat(extras[i]);
        }
		// alert(selected);
        var id = $("#remarks_area").data("id");
        // console.log(input_extras);
		// alert(id);
		// var input_extras=input_extras*quantity;
		// alert(input_extras);
        // console.log(selected.toString().split("_").join(" "));
		 if(subpro_price =="")
         {
            subpro_price =0;
         }
		 
		
		   // alert(subpro_price);
		   
		  var input_extras = parseFloat(input_extras)+parseFloat(sub_value);
		  // alert(subextra);
        // var subtotal = qty*subtotal;
        // var subtotal = (qty*rmk_value)+sub_pro;
        // alert(parseFloat(subtotal));
        document.getElementById("subtotal"+data_id).value = parseFloat(input_extras).toFixed(2);
        var unitPrice = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='price[]']").val());
		// alert(unitPrice);
        // console.log(unitPrice);
        $(".introduce-remarks.selected").parent().parent().find("input[name='subpro_price[]']").val(parseFloat(input_extras).toFixed(2));
        $(".introduce-remarks.selected").parent().parent().find("input[name='subtotal[]']").val((input_extras + unitPrice < 0) ? 0 : ((input_extras*quantity) + (unitPrice*quantity)).toFixed(2));
        $(".introduce-remarks.selected").siblings("input[name='extra']").val(extras);
        $("input[name='single_ingredients'].selected").siblings("input[name='extra']").val(extras);
        if(!$(".introduce-remarks.selected").parent().hasClass("pop_model")){
          $("a.introduce-remarks.selected").html((selected.toString() == '') ? "Remarks" : selected.toString().split("_").join(" "));
        }else{
          $("#remark_td").html((selected == '') ? "" : selected.toString().split("_").join(" "));
        }
		  $(".introduce-remarks.selected").parent().parent().find("input[name='remark_val[]']").val(selected);
        $(".introduce-remarks.selected").removeClass("selected");
        $("input[name='ingredients'].selected").val('').val(selected).removeClass("selected");
        $("input[name='single_ingredients'].selected").val('').val(selected).removeClass("selected");
        if($("#remarks_area").hasClass("transaction")){
          if($("#remarks_area").hasClass("no-back")){
            $("#pop_cart[data-id='" + id + "']").attr("data-quantity", quantity);
            $("#pop_cart[data-id='" + id + "']").click();
            $(".text_add_cart[data-id='" + id + "']").closest(".quatity").val(quantity);
            $("#pop_cart").removeData("id");
            $("#remarks_area").removeClass("no-back");
          }else{
            $(".text_add_cart[data-id='" + id + "']").click();
            $("#pop_cart[data-id='" + id + "']").attr("data-quantity", quantity);
            $("#pop_cart[data-id='" + id + "']").parent().find(".quatity").val(quantity);
          }

          $("#remarks_area").removeData("id");
        }
		 var sst_rate = document.getElementById("sstvals").value;
   var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);

            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
			   // alert(sst);
              sst_amt = parseFloat(sst)+parseFloat(subtol);

            });
             $("#subtol").text(subtol.toFixed(2));
			 
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));
    });
    $(".manual_input").click(function(e){

      if($("#remark_input").attr("type") == "hidden"){
        $("#remark_input").attr("type","text");
      }else if($("#remark_input").attr("type") == "text"){
        $("#remark_input").attr("type","hidden").val('');
      }

      e.preventDefault();
    });

   
	$(document).on("click",'.without_varient', function(event) { 
	   
        event.preventDefault();
		var id = $(this).data("id");
		// alert(id);
		 // var id = $(obj).attr("data-id");
    // var id = obj.id;
    var ex_id = $('#extra_venrt').val();
    var last_add_id = $('#last_add_id').val();
    var rmk_val = $('#extraprice_'+id).val();
   // alert(rmk_val);
    var remark = $('#remark_'+id).val();
     var extravar = $('#extra_'+id).val();
     // document.getElementById("pop_cart").style.backgroundColor="#03a9f3";
    $.ajax({
      type: 'post',
      url: 'productData.php',
      data: {p_id:id,sp_id:ex_id,remark:remark,extravar:extravar,rmk_val:rmk_val,last_add_id:last_add_id},
      success: function (data) {
      //alert(data);
	 
      if( data != null ) {
			//alert('The product added');
            $('#check'+id).html('<i class="fa fa-plus"></i>');
			var new_last_add_id=parseInt(last_add_id) + 1;
			$('#last_add_id').val(new_last_add_id);
            $('#posData').append(data);
           
                   $('#extra_venrt').val("");
               
                
                 
           //  }
		var sst_rate = document.getElementById("sstvals").value;
          var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);

            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
			   
              sst_amt = parseFloat(sst)+parseFloat(subtol);

            });
             $("#subtol").text(subtol.toFixed(2));
			 // alert(sst.toFixed(2));
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));

       }
	     initializeNumpad();
      }
   });
     
	});
	 $(document).on("click", '.product_cart', function(event) { 
    $('#varient_error').hide();   
     var content = "";
       var id = $(this).data("id");
	   // alert(id);
     var varient_count=$('#varient_count').val();
     var extra_value=$('#extra_value').val();
     varient_count++;
    
     $('#varient_count').val(varient_count);
    var p_price = $(this).data("price");
    var name = $(this).data("name");
    // alert(p_price);
    var p_price=parseFloat(p_price).toFixed(2);
    var p_pop_price=$("#p_pop_price").val();
    var c_id="prodct_cart_"+id;
    // alert(p_pop_price);
    var sum=parseFloat(p_price)+parseFloat(p_pop_price);
    // var sum= parseFloat((p_price).toFixed(2));
    var sum=parseFloat(sum).toFixed(2);
    // alert(p_price);
	    var new_extra=parseFloat(p_price)+parseFloat(extra_value);
    $("#extra_value").val(new_extra);
	var extra_value=$('#extra_value').val();
	 // alert(extra_value);
    $("#p_pop_price").val(sum);
    $("#pr_total").html("<b>"+sum+"</b>");
    $("#varient").append("<br/>-"+name+"(Rm "+p_price +")");
    var old_varent=$("#varient_type").html();
	// alert(old_varent);
    $("#varient_type").html(old_varent+","+id+"");
       
    // var content="<p data-id="+id+" data-pr="+p_price+">"+name +"  : Rm "+p_price +"<button  data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'>X</button></p>";
    // content +="<div  id='prodct_cart_"+id+"' data-name='"+name+"' data-id='"+id+"' data-price='"+p_price+"' class='ingredient product_cart'>";
              // content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+name+"' data-id='"+name+"' data-price='"+p_price+"' aria-label='Close'>";
              // content +="<span aria-hidden='true'>X</span>";
              // content +="</button><span class='ingredient-name'>"+name+" &nbsp; Price Rm "+p_price+"</span></div>";
            
    // alert(content);
     var link = document.getElementById(c_id);
    link.style.display = 'none'; //or
     // $("#product_info").append(content); 
         $("#product_table").append("<tr><td> &nbsp;&nbsp;<button data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'><i class='fa fa-remove'></i></button>-"+name+" </td><td> "+p_price+" </td></tr>");       
     content='';
  });
    $(document).on("click", '.removevarient', function(event) { 
      var varient_count=$('#varient_count').val();
	   var extra_value=$('#extra_value').val();
     varient_count--;
     // alert(varient_count);
     $('#varient_count').val(varient_count);
     var id = $(this).data("id");
     var price = $(this).data("pr");
      var price=parseFloat(price).toFixed(2);
	 // alert(price);
     var name = $(this).data("name");
     var c_id="prodct_cart_"+id;
     var p_pop_price=$("#pr_total").text();
     var p_pop_price=parseFloat(p_pop_price).toFixed(2);
     var old_varent=$("#varient_type").html();
     var varent_list=$("#varient").html();
     // alert(varent_list);
     var r_key="<br>-"+name+"(Rm "+price +")";
    
     var ex = $('#extra_venrt').val();
   
    
   
     var new_varient_list = varent_list.replace(r_key,'');
     var new_vareint = old_varent.replace(id,'');
    $("#varient").html(new_varient_list);
    $("#varient_type").html(new_vareint);
     var link = document.getElementById(c_id);
    link.style.display = 'block'; //or
	// alert(p_pop_price);
     var sum=parseFloat(p_pop_price)-parseFloat(price);
     var newextra=parseFloat(extra_value)-parseFloat(price);
      var sum=parseFloat(sum).toFixed(2);
      var newextra=parseFloat(newextra).toFixed(2);
	   $("#extra_value").val(newextra);
      // alert(sum);
    //$("#p_pop_price").val(sum);   
    $("#pr_total").html("<b>"+sum+"</b>");
	 $("#p_pop_price").val(sum);
    jQuery(this).closest('tr').remove();
    
  });
   $(document).on("click", '#pop_cart', function(event) {   
   // alert(3);
   var varient_must=$('#varient_must').val();
   var extra_value=$('#extra_value').val();
   var go_ahead="y";
   // alert(extra_value);
   if(varient_must=="y")
   {
     var varient_count=$('#varient_count').val(); 
     if(varient_count>0)
     {
      
     }
     else
     {  
       var go_ahead="n";
       $('#varient_error').show(); 
     }
   }
   else   
   {
    var go_ahead="y";
   }
   // alert(go_ahead);
   if(go_ahead=="y")
   {
     var id = $(this).data("id");
		// alert(id);
		 // var id = $(obj).attr("data-id");
    // var id = obj.id;
    var ex_id = $('#extra_venrt').val();
    var rmk_val = $('#extraprice_'+id).val();
   // alert(rmk_val);
    var remark = $('#remark_'+id).val();
     var extravar = $('#extra_'+id).val();
	  var last_add_id = $('#last_add_id').val();
	  var varient_type=$("#varient_type").html();
	  // alert(varient_type);
     // document.getElementById("pop_cart").style.backgroundColor="#03a9f3";
    $.ajax({
      type: 'post',
      url: 'productData.php',
      data: {p_id:id,sp_id:ex_id,remark:remark,extravar:extravar,rmk_val:rmk_val,extra_value:extra_value,varient_type:varient_type,last_add_id:last_add_id},
      success: function (data) {
      //alert(data);
      if( data != null ) {
			//alert('The product added');
            $('#check'+id).html('<i class="fa fa-plus"></i>');
			var new_last_add_id=parseInt(last_add_id) + 1;
			$('#last_add_id').val(new_last_add_id);
            $('#posData').append(data);
           
                   $('#extra_venrt').val("");
               
                
                 
           //  }
		var sst_rate = document.getElementById("sstvals").value;
          var qd=0;
          var subtol = 0;
          var sst =0;
            $(".qd").each(function()
             {
                qd = parseInt(qd)+parseInt($(this).val());
             });
            $("#qty").text(qd);

            $(".subt").each(function(){

              subtol = parseFloat(subtol)+parseFloat($(this).val());
               sst = subtol * sst_rate/100;
              sst_amt = parseFloat(sst)+parseFloat(subtol);

            });
             $("#subtol").text(subtol.toFixed(2));
             $("#sst").text(sst.toFixed(2));
             $("#my_sst").text(sst_rate);
              $("#sst_amt").text(sst_amt.toFixed(2));
              $("#Payment").text(sst_amt.toFixed(2));
			  

       }
	    initializeNumpad();
      }
   });
    $("#ProductModel").modal("hide");
	 $("#varient_type").html('');   
      $("#varient").html(''); 
   }
   else
   {
    $('#varient_error').show();
   }
   $('#extra_value').val(0);
  });
	$(document).on("click",'.with_varient', function(event) { 
	   event.preventDefault();
    // $(this).hide();
    console.log("Hola");
	// alert(4);
    var p_price = $(this).data("pr");
    console.log(p_price);   
     $('#varient_count').val(0);
     $("#product_main").html("");
     $("#product_table").html("");
    var id = $(this).data("id");
    var varient_must = $(this).attr("data_varient_must");
    $('#varient_must').val(varient_must);
    var child_id="child_"+id;
	// alert(child_id);
    var product_child_id="product_child_"+id;
    var subproduct_selected = '';
    var single_remarks = '';
    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
	// alert(single_remarks);
    var p_extra = $(this).parent().parent().find("input[name='extra']").val();
    
    var code = $(this).data("code");
    var name = $(this).data("name");
    var quantity = $(this).siblings(".quantity").children(".quatity").val();
        // alert(quantity);
    // var p_total = p_price*quantity;
    p_total = parseFloat(p_price).toFixed(2);
    $("#varient_name").html(name);
        $("#p_pop_price").val(p_total);     
         $("#product_table").append("<tr><td> "+name+" </td><td> "+p_total+" </td></tr>");  
       $("#pr_total").html("<b>"+p_total+"</b>");
       // $("#remark_td").html((single_remarks == '') ? "" : single_remarks.toString().split("_").join(" "));
      // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");
      $(".pop_model").html("<div class='row' style='width:11em'><div class='col-md-12' style='display:none;'>Quantity: <input name='quantity_input' type='hidden' class='quatity' value='1' style='width:2em;text-align:center' min='0' max='99'></div></div><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-pr=" + p_price + " data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");
        

      for(var i = 0; i < subproducts_global.length; i++){
        for(var j = 0; j < subproducts_global[i].length; j++){
          if(subproducts_global[i][j]['product_id'] == id){
            subproduct_selected = subproducts_global[i];
            break;
          }
        }
      }
      // console.log(subproduct_selected);
      var exists_in_subproducts = false;
      for(var i = 0; i < subproduct_selected.length; i++){
        if(subproduct_selected[i]['product_id'] == id){
          exists_in_subproducts = true;
          break;
        }
      }
      if(exists_in_subproducts){   

        var content = '';
        for(var i = 0; i < subproduct_selected.length; i++){
          content +="<div  id='prodct_cart_"+subproduct_selected[i]['id']+"' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' class='ingredient product_cart'>";
              content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' aria-label='Close'>";
              content +="<span aria-hidden='true'><i class='fa fa-plus'></i></span>";
              content +="</button><span class='ingredient-name'>"+subproduct_selected[i]['name']+" &nbsp; Price Rm "+subproduct_selected[i]['product_price']+"</span></div>";
          // console.log(content);
          
        }

        $("#product_main").html(content);
        $("#ProductModel").modal("show");

      }
  });
  
$("div").on( "click", "spam", function( event ) {
  $(event.delegateTarget ).css( "background-color", "green");
});

 $(document).on('click', '.kb-text', function () {
            display_keyboards();
        });



  });


 </script>
 
