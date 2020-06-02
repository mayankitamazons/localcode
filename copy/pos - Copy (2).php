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
 $_SESSION['mm_id']= $loginidset;
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
    <script type="text/javascript" src="pos/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="pos/jquery-migrate-1.2.1.min.jsg"></script>
     <script type="text/javascript" src="pos/jquery.keyboard.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.extension-all.min.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>

    
    <style type="text/css">
        
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
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
      //  var price = document.getElementById("price"+p).value;
        var rmk_value = document.getElementById("rmk_value"+p).value;
         var subpro_price = document.getElementById("subpro_price"+p).value;
        // alert(subpro_price);
         if(subpro_price =="")
         {
            subpro_price = "0.00";
         }
        var subtotal = qty*rmk_value;
        //alert(subtotal);
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
      url:'getresult.php',
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

  function getInputManual()
  {
      if($("#remark_input").attr("type") == "hidden"){
        $("#remark_input").attr("type","text");
      }else if($("#remark_input").attr("type") == "text"){
        $("#remark_input").attr("type","hidden").val('');
      }
    }

    function getIngradiant(ind)
    {

      var  val_extra =[];
      var newingradiant=[];

    if($("label.ingrat_"+ind).hasClass('active'))
      {    
		  
    //$("#remarks_area .btns.btn_sec.ingrat").removeClass("checkbox-checked").removeClass("active");
     $("#remarks_area .btns.btn_sec.ingrat_"+ind).removeClass("checkbox-checked").removeClass("active");
      	  
		  $("#extra_"+$("#saveAdd").attr("data-pro-id")).val("");
		  
		  $("#extraprice_"+$("#saveAdd").attr("data-pro-id")).val("");  
		
		
      }else{

		  $("#remarks_area .btns.btn_sec.ingrat_"+ind).addClass("checkbox-checked").addClass("active");
          var x=0;
          $('.btn_check').each(function() {

        if($("label.ingrat_"+x).hasClass('active'))
          {  
          // newingradiant.puch($(this).val());
           //val_extra.puch($(this).parent().siblings(".extra-price-ingredient").html());
          newingradiant = newingradiant+','+$(this).val();
          val_extra= val_extra +','+ $(this).parent().siblings(".extra-price-ingredient").html();
          }
         // alert(val_extra);
         //  val_extra = $.trim($(this).siblings(".extra-price-ingredient").html());
        x++;
          });
         
		  $("#extra_"+$("#saveAdd").attr("data-pro-id")).val(newingradiant);  
		  $("#extraprice_"+$("#saveAdd").attr("data-pro-id")).val(val_extra);  
      }

     }
     function reset_remark()
     {
      var id = $("#reset_remark").attr("data-dp-id");
      $('#extraprice_'+id).val("");
      $('#extra_'+id).val("");
      $('#remark_'+id).val("");
      $("#remarks_area .btns.btn_sec.ingrat").removeClass("checkbox-checked").removeClass("active");
     }
     
     function saveAdd()
    {

       var id = $('#p_id').val();
        $('#ProductModel').attr("data-id", id)
       if($("label.ingrat").hasClass('active'))
        {
          $('label.btn_sec.ingrat.checkbox-checked.active').each(function() {
          var newingradiant = $(this).children("input[name='ingredient']").val();
            val_extra = $(this).siblings(".extra-price-ingredient").html();
           // alert(val_extra);
        
          });
        } 
          var remark_input = $('#remark_input').val();
          $('#extra_rem').val(remark_input);
          $('#ProductModel').modal('show');
    }
  
  function inputRemark()
  {
      var ids = $("#remark_input").attr("data-id");
	  //alert(ids);
      var x = document.getElementById("remark_input").value;
      $("#remark_"+$("#remark_input").attr("data-id")).val(x);

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
                                            <th width="15%">Price</th>
                                            <th width="15%">Extra</th>
                                            <th width="10%">Qty</th>
                                            <th width="20%">Subtotal</th>
                                            <th width="15%">Remark</th>
                                            
                                          
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
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            Payment :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #5f5e7d; font-weight:bold; background:#5f5e7d; color:#FFF;" colspan="2">
                                            <span id="Payment">RM 0.00</span>
                                        </td>
                                    </tr>
                                     <tr>
                                  
                                      <td style="padding: 5px 10px; border-top: 1px solid #f0ad4e; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                            Table Number :</td>
                                        <td class="text-right" style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #51d2b7; border-bottom: 1px solid #f0ad4e; font-weight:bold; background:#f0ad4e; color:#FFF;" colspan="2">
                                           <div class="input-group">
                                   <!--   <textarea name="payment_note[]" id="payment_note_2" class="pa form-control kb-text" aria-haspopup="true" role="textbox"></textarea>-->
                                           <input class="form-control kb-text " value="<?php if(isset($_GET['t'])){ echo $_GET['t'];} ?>" onclick="display_keyboards()" id="table" name="table" type="text" />
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
                                    <div class="row">
                                        
                                        <div class="col-xs-6" style="padding: 0;">
                                             <button type="reset" class="btn btn-danger btn-block btn-flat" id="reset" style="height:67px;">Clear All</button>
                                        </div>
                                        <div class="col-xs-6" style="padding: 0;">
                                            <button type="submit" class="btn btn-success btn-block" id="" style="height:67px;">
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

                    <div id="cp">
                        <div id="cpinner" style="background-color: #51d2b7;"  class="col-lg-12 col-xs-6">
                            <div class="quick-menu">
                                <div id="proContainer">
                                    <div id="ajaxproducts">
                                        <div class="col-sm-12  text-center" >
                                        <div class="col-sm-6 text-center" >
                                            <H2>Select Product</H2>
                                        </div>
                                        <input type="hidden" name="sstvals" id="sstvals" value="<?php echo $merchant_detail['sst_rate'];?>">
                                           <div class="col-sm-6 text-center example" >
                                           <input type="text" placeholder="Search.." name="search2" id="search2">
                                            <button type="button" onclick="getSearch()"><i class="fa fa-search"></i></button>
                                          
                                           </div>
                                                  <script>
                                                getresult("getresult.php");
                                                </script>


                                        <div class="btn-group btn-group-justified pos-grid-nav">
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
          <div class="modal-dialog" style="width: 500px;">
              <div class="modal-content" style="border-radius: 6px;">
                  <div class="modal-header">
                      <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                      <h4 class="modal-title" style="font-size:20px;font-family: 'Nunito Sans', sans-serif;font-weight: 400;">Remarks</h4>
                  </div>
                  <div class="modal-body">
                    <?php 
                      $ingredients = json_decode(mysqli_fetch_row(mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'"))[0]);
                       $ind = 0;
                      foreach ($ingredients as $ingredient) {
                        if(!empty($ingredient)){
                          // $ingName = (sizeof(explode("[", $ingredient)) > 1) ? explode("[", $ingredient)[0] : $ingredient;
                          $ingName = $ingredient->name;
                          $ingPrice = ($ingredient->price == 0) ? null : $ingredient->price;
                          // $ingPrice = (!empty(explode("]",explode("[", $ingredient)[1])[0])) ? explode("]",explode("[", $ingredient)[1])[0] : null;

                          ?>
                           <div style="margin: 10px 8px;" class="btn-group"  onclick="getIngradiant(<?php echo $ind;?>)" data-ingred-id='<?php echo $ingredient->name;?>' data-toggle="buttons" data-subcategory='<?php echo $ingredient->subcategory; ?>'>
                              <label class="btns btn_sec ingrat_<?php echo $ind;?>">
                                <input type="checkbox" class="btn_check" name="ingredient" id="ingredient" value="<?php echo $ingName; ?>" autocomplete="off"> 
                                <?php
                                    echo ucfirst(str_replace("_", " ",$ingName)); 
                                ?>
                              </label>
                              <?php  if(!is_null($ingPrice)){ ?><div class='extra-price-ingredient'><?php echo number_format($ingPrice,2,".",""); ?></div><?php } ?>
                            </div>
                            <?php
                        }
                        $ind++;
                      }

                    ?>
                    <input type="hidden" name="remark" id="remark_input"  onkeyup="inputRemark()" class="myform_con" style="margin: 10px 0" data-id="" placeholder="Write here your remarks"/>
                    <div id="small_text" style="position:relative;margin-top:2em;width:100%;">
                      <small style="position: absolute;bottom: 2px;left: 5px;font-weight: bold;color:red;">Note : e.g. 0.30 = Rm 0.30</small>
                    </div>
                  </div>
                  <div class="modal-footer" style="position: relative;">
                    <button type="button" id="reset_remark" data-dp-id="" onclick="reset_remark(this)" class="cancel_close" data-dismiss="modal">Cancel</button>
                   <!-- <button type="button" class="save_close" id="saveAdd" data-pro-id="" onclick="saveAdd()">
                    Save and
                      <i class="fa fa-plus"></i>
                   
                    </button> -->
                    <button role="button" id="saveAdd" class="save_close pro_status introduce-remarks bnt_add" data-pro-id="" data-toggle="modal" data-target="#ProductModel"> Save and<i class="fa fa-plus"></i></button>
                    <button type="button" class="manu_btn  manual_input" onclick="getInputManual();return false;" id="manual_input">Manual input</button>
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
                    <button role="button" style="min-height:40px;position:static !important;" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal" data-target="#remarks_area" disabled="">Remarks</button>
                    <button role="button" class="close_pop btn btn-large btn-primary" style="background:#50D2B7;border:none;">Ok</button>
                  </div>    
                                </div>
                            </div>
    </div>

</body>
</html>

<script type="text/javascript">
      var subproducts_global = [];
      var products_id_global = [];
  </script>
<script type="text/javascript">

  $( document ).ready(function() {
	    var showModal = true;
    $("#reset").click(function(){
		$(".pos_data").remove();
		$('#qty').html('0.00');
		$('#subtol').html('0.00');
		$('#service_charge').html('0.00');
		$('#sst').html('0.00');
		$('#sst_amt').html('0.00');
		$('#Payment').html('0.00');
	
	});
	
	$(document).on("click",'.without_varient', function(event) { 
		var id = $(this).data("id");
		// alert(id);
		 // var id = $(obj).attr("data-id");
    // var id = obj.id;
    var ex_id = $('#extra_venrt').val();
    var rmk_val = $('#extraprice_'+id).val();
   // alert(rmk_val);
    var remark = $('#remark_'+id).val();
     var extravar = $('#extra_'+id).val();
     // document.getElementById("pop_cart").style.backgroundColor="#03a9f3";
    $.ajax({
      type: 'post',
      url: 'productData.php',
      data: {p_id:id,sp_id:ex_id,remark:remark,extravar:extravar,rmk_val:rmk_val},
      success: function (data) {
      //alert(data);
      if( data != null ) {
			//alert('The product added');
            $('#check'+id).html('<i class="fa fa-plus"></i>');
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
      }
   });
     
	});
	$(document).on("click",'.with_varient', function(event) { 
	  
    // $(this).hide();
    console.log("Hola");
    var p_price = $(this).data("pr");
    console.log(p_price);
     $('#varient_count').val(0);
     $("#product_main").html("");
     $("#product_table").html("");
    var id = $(this).data("id");
    var varient_must = $(this).attr("data_varient_must");
    $('#varient_must').val(varient_must);
    var child_id="child_"+id;
    var product_child_id="product_child_"+id;
    var subproduct_selected = '';
    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
	alert(single_remarks);
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
       $("#remark_td").html((single_remarks == '') ? "" : single_remarks.toString().split("_").join(" "));
      // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");
      $(".pop_model").html("<div class='row' style='width:11em'><div class='col-md-12'>Quantity: <input name='quantity_input' type='number' class='quatity' value='1' style='width:2em;text-align:center' min='0' max='99'></div></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-pr=" + p_price + " data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");
        

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
  });


 </script>
