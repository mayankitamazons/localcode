$(document).ready(function(){
     var ingredients = [];
     var state = 1;
     var startdate = null;
     var enddate = null;
	
    $(".sidebar-toggle").click(function(){
        if($("aside.site-sidebar").hasClass("displayed")){
            $("aside.site-sidebar").removeClass("displayed").addClass("notdisplayed");
        }else{
            $("aside.site-sidebar").removeClass("notdisplayed").addClass("displayed");
        }
    })
    $(".heart.fa").click(function() {
        var className = $(this).attr('class');
        var favorite = "0";
        if(className.indexOf("fa-heart-o") > -1){
            favorite = "1";
        }
        var data = {method: "favorite", favorite: favorite, merchant_id: $(".merchant_id").val(), user_id: $(".user_id").val() };
        $.ajax({
            url:"functions.php",
            type:"post",
            data:data,    
            success:function(data){
                console.log(data);
            
            }
        });
        $(this).toggleClass("fa-heart fa-heart-o");
    });

    $(".language_option").change(function(e){
        window.location.href="?language="+e.target.value;
    });
    var unread_array = [];
    var unread_num = 0;
    // setInterval(function(){ 
        // var data = {id: $(".user_id").val(), method: "getUnreadMsg"};
        // unread_array = [];
        // unread_num = 0;
        // $.ajax({
            // url:"functions.php",
            // type:"post",
            // data:data,    
            // dataType: 'json',
            // success:function(data){
                // if(data.length > 0){
                    // for(var i = 0; i < data.length; i++){
                        // var obj = new Object();
                        // obj.sender = data[i].sender;
                        // obj.num = data[i].num;
                        // unread_array.push(obj);
                        // unread_num += parseInt(data[i].num);
                        // $(".unread_num").html(unread_num);
                    // }
                // } else {
                    // $(".unread_num").html("");
                // }
            
            // }
        // }); 
        
    // }, 3000);
	
   
    var unPrintedOrders = [];
    setInterval(function() {
        unPrintedOrders = [];
		
        var data = {id: $(".user_id").val(), method: "getUnPrintedOrder2"};
		// alert($(".user_id").val());
        $.ajax( {
            url : "functions.php",
            type:"post",
            data : data,
            dataType : 'json',
            success : function(data) {
                console.log(data);
                if( data.length > 0 ) {
                    for( var i = 0 ; i < data.length ; i ++ ) {
                        var order = data[i];
                        updateOrder( order );
                    }
                }
            }
        } );
    }, 5000);

    function updateOrder(order) {
        console.log(order);
        if( order['id'] ) {
            console.log(order);
            var status = order['status'];
            //&& order['wallet'] != ""
            if(status == 0 && order['printed'] != 1 && order['printed'] != '1' && order['user_id'] != 0 && order['user_id'] != '0'  ) {
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: {id: order['id'], printed: 1, method : 'updatePrinted'},
                    success: function (data) {
                        console.log(data);
                       
                    }
                });
            }
			 printOrder(order);
        }
    }

    function printOrder( order ) {
        var data = {order: order, method: "pintOrder", date : getCurrentDate() , time : getCurrentTime()};
        $.ajax( {
            url : "functions.php",
            type:"post",
            data : data,
            dataType : 'json',
            success : function(data) {
                if( data.indexOf( 'print_setting_error' ) > -1 ) {
                    alert("You need to set print ip address in profile page.");
                } else {
                    console.log(data);
                }
            }});
    }

    function getCurrentTime() {
        var today = new Date();
        var hh = today.getHours();
        var mm = today.getMinutes();
        var ss = today.getSeconds();
        return hh + ':' + mm + ':' + ss;
    }

    function getCurrentDate() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!

        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        switch( mm ) {
            case 1:
                mm = 'Jan';
                break;
            case 2:
                mm = 'Feb';
                break;
            case 3:
                mm = 'Mar';
                break;
            case 4:
                mm = 'Apr';
                break;
            case 5:
                mm = 'May';
                break;
            case 6:
                mm = 'Jun';
                break;
            case 7:
                mm = 'Jul';
                break;
            case 8:
                mm = 'Aug';
                break;
            case 9:
                mm = 'Sep';
                break;
            case 10:
                mm = 'Oct';
                break;
            case 11:
                mm = 'Nov';
                break;
            case 12:
                mm = 'Dec';
                break;
        }
        return dd + '/' + mm + '/' + yyyy;
    }


    $(".unread").click(function(e){
        if(unread_array.length > 0){
            var sender_id = $(".sender_id").val();
            var len = unread_array.length;
            window.open('/chat/chat.php?sender='+sender_id+'&receiver='+unread_array[len - 1].sender, '_blank')
            unread_num = unread_num - unread_array[len-1].num;
            $(".unread_num").html(unread_num);
            unread_array.splice(len-1, 1); 
        }
             
    });

      // Ingredients managing

    $("#add-ingredient").click(function(){
        $(".tuto").slideUp();
        if($("input[name='new-ingredient']").val() == ''){
           alert("The ingredient's input is empty");
        }else{
            var subcateg = ($("#category-select").val() == "Subcategory" ? "all" : $("#category-select").val());
            var subcateg_name = $("option[value='" + subcateg + "']").html();
            subcateg_name = (subcateg_name == "Subcategory") ? "All subcategories" : subcateg_name;
            if($("input[name='price-ingredient']").val() != '' && $("input[name='price-ingredient']").val() != 0){
                var array_complete_ingredient = {name:$("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_"), price:$("input[name='price-ingredient']").val(), subcategory: subcateg};
                // var complete_ingredient = $("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_") + "[" + $("input[name='price-ingredient']").val() + "]";
            }else{
                var array_complete_ingredient = {name:$("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_"), price:0, subcategory: subcateg};
                // var complete_ingredient = $("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_");
            }
			// alert(ingredients_general_doc);
            ingredients.push(array_complete_ingredient);
            // console.log(ingredients_general_doc);
            ingredients_general_doc.push(array_complete_ingredient);
            // console.log("Less than 1");
            var ingredientName = $("input[name='new-ingredient']").val().charAt(0).toUpperCase() + $("input[name='new-ingredient']").val().slice(1).toLowerCase();
            if($("input[name='price-ingredient']").val() != '' && $("input[name='price-ingredient']").val() != 0){
                $("#ingredients_container").append("<div class='ingredient'><button type='button' class='btn btn-info remove-ingredient' aria-label='Close' ><span aria-hidden='true'>&times;</span></button><span class='ingredient-name'>" + ingredientName + "</span><span class='subcategory-ingredient' data-id='" + subcateg + "'>" + subcateg_name + "</span><div class='extra-price-ingredient'>" + $("input[name='price-ingredient']").val() + "</div><input type='hidden' name='ingredient-name-input' value=" + $("input[name='new-ingredient']").val().replace(/ /g,"_") + " /></div>");
            }else{
                $("#ingredients_container").append("<div class='ingredient'><button type='button' class='btn btn-info remove-ingredient' aria-label='Close' ><span aria-hidden='true'>&times;</span></button><span class='ingredient-name'>" + ingredientName + "</span><span class='subcategory-ingredient' data-id='" + subcateg + "'>" + subcateg_name + "</span><input type='hidden' name='ingredient-name-input' value=" + $("input[name='new-ingredient']").val().replace(/ /g,"_") + " /></div>");
            }

            $("input[name='new-ingredient'], input[name='price-ingredient']").val('');
            // console.log(ingredients_general_doc);
        }
    });
    $("input[name='new-ingredient'],input[name='price-ingredient']").keyup(function(e){
        if(e.keyCode === 13){
            $(".tuto").slideUp();
            if($("input[name='new-ingredient']").val() == ''){
                alert("The ingredient input is empty");
            }else{
                var subcateg = ($("#category-select").val() == "Subcategory" ? "all" : $("#category-select").val());
                var subcateg_name = $("option[value='" + subcateg + "']").html();
                subcateg_name = (subcateg_name == "Subcategory") ? "All subcategories" : subcateg_name;
                // subcateg = (subcateg == 'Subcategory') ? "Subcategory" : subcateg;
                if($("input[name='price-ingredient']").val() != '' && $("input[name='price-ingredient']").val() != 0){
                    var array_complete_ingredient = {name:$("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_"), price:$("input[name='price-ingredient']").val(), subcategory: subcateg};
                    // var complete_ingredient = $("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_") + "[" + $("input[name='price-ingredient']").val() + "]";
                }else{
                    var array_complete_ingredient = {name:$("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_"), price:0, subcategory: subcateg};
                    // var complete_ingredient = $("input[name='new-ingredient']").val().toLowerCase().replace(/ /g,"_");
                }
                ingredients.push(array_complete_ingredient);
                ingredients_general_doc.push(array_complete_ingredient);
                var ingredientName = $("input[name='new-ingredient']").val().charAt(0).toUpperCase() + $("input[name='new-ingredient']").val().slice(1).toLowerCase();
                if($("input[name='price-ingredient']").val() != '' && $("input[name='price-ingredient']").val() != 0){
                    $("#ingredients_container").append("<div class='ingredient'><button type='button' class='btn btn-info remove-ingredient' aria-label='Close' ><span aria-hidden='true'>&times;</span></button><span class='ingredient-name'>" + ingredientName + "</span><span class='subcategory-ingredient' data-id='" + subcateg + "'>" + subcateg_name + "</span><div class='extra-price-ingredient'>" + $("input[name='price-ingredient']").val() + "</div><input type='hidden' name='ingredient-name-input' value=" + $("input[name='new-ingredient']").val().replace(/ /g,"_") + " /></div>");
                }else{
                    $("#ingredients_container").append("<div class='ingredient'><button type='button' class='btn btn-info remove-ingredient' aria-label='Close' ><span aria-hidden='true'>&times;</span></button><span class='ingredient-name'>" + ingredientName + "</span><span class='subcategory-ingredient' data-id='" + subcateg + "'>" + subcateg_name + "</span><input type='hidden' name='ingredient-name-input' value=" + $("input[name='new-ingredient']").val().replace(/ /g,"_") + " /></div>");
                }
                $("input[name='new-ingredient'], input[name='price-ingredient']").val('');
                // $("input[name='ingredients']").val(ingredients);
                // console.log(ingredients);
            } 
        }
        // console.log(ingredients_general_doc);
    });


    $("#ingredients_container").on('click','.remove-ingredient', function(e){
        var ingName = $(this).siblings("input[name='ingredient-name-input']").val().toLowerCase();
        var ingPrice = $.trim($(this).siblings(".extra-price-ingredient").html());
        $(this).parent().remove();
        var index = ingredients.indexOf(ingName);
        if (index > -1) {
            ingredients.splice(index, 1);
        }
        ingredients_general = ingredients;

        for(var i in ingredients_general_doc){
            if(ingredients_general_doc[i].name == ingName && ingredients_general_doc[i].price == ingPrice){
                ingredients_general_doc.splice(i, 1);
            }
        }
    });

    $("body").on("click","#update-ingredients", function(e){
        ingredient_general = JSON.stringify(ingredients_general_doc);
        // console.log("ingredient_general:");
        // console.log(ingredient_general);

        $(".tuto").slideUp();
        $.post("./remark.php", {
            update_ingredients: ingredient_general
        }, function(data, result){
            if(data == 1){
               alert("List updated!") 
            }else{
                alert("An error occured updating the list, try again later");
                console.log(data);
            }
        });
        e.preventDefault();
    });

    $("a[href='#tutorial']").click(function(e){
        e.preventDefault();
        $(".tuto").slideToggle();
    });

    $("body").on("click","#diary", function(e){
        var xmlHttp;
        function srvTime(){
            try {
                xmlHttp = new XMLHttpRequest();
            }
            catch (err1) {
                try {
                    xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
                }
                catch (err2) {
                    try {
                        xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
                    }
                    catch (eerr3) {
                        alert("AJAX not supported");
                    }
                }
            }
            xmlHttp.open('HEAD',window.location.href.toString(),false);
            xmlHttp.setRequestHeader("Content-Type", "text/html");
            xmlHttp.send('');
            return xmlHttp.getResponseHeader("Date");
        }
        
        var st = srvTime();
        var date = new Date(st);
        if (state == 1) {
            startdate = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
            state = 2;
        } else if (state == 2) {
            enddate = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
            state = 1;
        }
        $.post('editreport.php?startdate='+startdate+'&enddate='+enddate+'&state='+state, function(){
            if (state == 2) {
                alert('Start Work');
            } else if(state == 1) {
                alert('End Work');
            }
        });
    });

    if(window.location.pathname === "/remark.php" || window.location.pathname === "/koofamilies/remark.php"){
        $.post("./remark.php", {subcat:true},function(data, success){
            var json_data = JSON.parse(data);
            var content = '';
            for(var i = 0; i < json_data.length; i++){
                content += "<option value='" + json_data[i].id + "'>" + json_data[i].category_name + "</option>"
            }
            $("#category-select").append(content);
        });
    }

});