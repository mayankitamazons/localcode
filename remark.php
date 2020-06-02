<?php 
include("config.php");
# ACLARATION:
#	To have a fully working remark system
#	you'll need to have a column in 'users' table
#	
#	If this column does not exist remark WON'T work
#
// var_dump($_POST);
$ingredients_general = [];
if(!isset($_SESSION['login'])){
	header("location:login.php");
}

if(isset($_POST['subcat'])){
	$id = $_SESSION['login'];
	$q = mysqli_query($conn,"SELECT * FROM category WHERE user_id ='".$id."' and status=0 ");
	if(!$q){die(false);}
	$data = mysqli_fetch_all($q, MYSQLI_ASSOC);
	echo json_encode($data);
	die();
}


if(isset($_POST['update_ingredients'])){
	$words = json_encode($_POST['update_ingredients'],JSON_UNESCAPED_UNICODE);
	$test = json_decode($words);
	$id = $_SESSION['login'];
	$q = mysqli_query($conn,"UPDATE users SET preset_words=$words WHERE id='$id'");
	if(!$q){die(false);}
	die(true);
}

function loadIngredients($id){
	global $conn;

	$q = mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'");

	$data = mysqli_fetch_row($q);

	$ingredients = json_decode(trim($data[0], '\''));

	// var_dump($ingredients);

	foreach ($ingredients as $ingredient) {

		if(!empty($ingredient)){
			$ingName = $ingredient->name;
			$ingPrice = $ingredient->price;
			$ingSubcat = (!isset($ingredient->subcategory)) ? "All" : $ingredient->subcategory;
			// $ingSubcat = (!isset($ingredient->subcategory)) ? "All" : $ingredient->subcategory;
			if($ingSubcat == "all"){
				$subcategName = "All subcategories";
			}else{
				$query_subcat = mysqli_query($conn,"SELECT * FROM category WHERE id ='".$ingSubcat."'");
				$data = mysqli_fetch_all($query_subcat, MYSQLI_ASSOC);
				$subcategName = $data[0]['category_name'];
			}
			?>
			<div class="ingredient">
				<button type="button" class="btn btn-info remove-ingredient" aria-label="Close"><span aria-hidden="true">×</span></button>
				<span class="ingredient-name"><?php echo ucfirst(str_replace("_", " ", $ingName)); ?></span>
				<span class='subcategory-ingredient' data-id='<?php echo $ingSubcat; ?>'><?php echo $subcategName; ?></span>
				<?php if($ingPrice != 0){ ?><div class='extra-price-ingredient'><?php echo $ingPrice; ?></div><?php } ?>
				<input type="hidden" name="ingredient-name-input" value="<?php echo $ingredient->name; ?>">
				<input type="hidden" name="ingredient-subcat-input" value="<?php echo $ingredient->subcat; ?>"></div>
			<?php
		}
	}
	// echo "<input type='hidden' name='ingredients' value='" . ((!empty($data[0])) ? strtolower($data[0]) : '') . "'/>";
	return empty($data[0]) ? "" : json_encode($ingredients);
}
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	.ingredient{
		position: relative;
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
		max-width: 50%;
	}
	#ingredients_container{
		display: grid;
		grid-template-columns: 1fr 1fr;
	}
	.extra-price-ingredient{
		position: absolute;
		width: 40px;
		height: 40px;
		display: grid;
		vertical-align: middle;
		align-content: center;
		text-align: center;
		right: -15px;
		top: -15px;
		border-radius: 50%;
		padding: 10px;
		background: #fff;
		border: 1px solid #03a9f3;
	}
	.subcategory-ingredient{
		position: absolute;
		max-width: 40%;
		height: 1.4em;
		padding-right: 20px;
		right: 0;
		top: -17px;
		bottom: 0;
		margin: auto;
	}
    @media (max-width: 767px) {
    	.subcategory-ingredient{
    		max-width: 100% !important;
    	}
    	#ingredients_container{
    		grid-template-columns: 1fr;
    	}
    	.ingredient{
    		width: 100%;
    		padding-top: 20px !important;
    	}
    	.ingredient > .subcategory-ingredient{
    		bottom: auto;
    		top: 0;
    		right: 0;
    		left: 0;
    		margin: auto;
    		text-align: center;
    		padding: 0;
    	}
	}
	</style>
	<script type="text/javascript">
		var ingredients_general_doc = [];
	</script>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
				<div class="container" >
				    <div class="row">
				        <div class="well col-md-10">
						
							<div class="form-group">
								<label>Ingredients</label><p style="color:#51d2b7;display:none;" class="tuto">Here you will see a preview of your keyword list.<br>You can introduce the keywords into the input down bellow, introduce one keyword by one or have them separated with commas.<br>If you want to add a extra price to your remark write the amount on the "price" input, it can be either a positive or negative number<br>If a keyword has a price it's written inside a circle in the top right corner</p>

								<div id="ingredients_container">
									<?php $ingredients_array = loadIngredients($_SESSION['login']);
									// echo (is_null($ingredients_array) || $ingredients_array == null) ? "I am null" : $ingredients_array;
									?>
								</div>
								<script type="text/javascript">
									var ingredients_general_doc = <?php echo (empty($ingredients_array) || is_null($ingredients_array)) ? '[]' : ("JSON.parse('" . addslashes($ingredients_array) . "')"); ?>;
								</script>
								<div class="form-row">
									<div class="col-md-6">
										<input type="text" name="new-ingredient" class="form-control" value="" style="margin:5px 0;" placeholder="Introduce the keywords. eg: More spice">
									</div>
									<div class="col-md-2">
										<input type="number" name="price-ingredient" class="form-control" value="" style="margin:5px 0;" placeholder="Price">
									</div>
									<div class="col-md-4">
										<select id="category-select" name="category-select" class="custom-select form-control" style="margin:5px 0">
											<option selected value="all">Subcategory</option>
										</select>
									</div>
								</div>
								<div id="add-ingredient" class="btn btn-outline-primary" >Add keyword</div>	<span class="tuto" style="color:#51d2b7;display:none;">To add the keyword into your temporal list click this button.</span>
							</div>
							<p style="color:#51d2b7;display:none;" class="tuto">
								Once you feel happy with your preview list, to update it to your actual list press the "Update remark list" button.
							</p>
							<button type="button" id="update-ingredients" class="btn btn-primary btn-lg btn-block">Update remark list</button>

						</div>
					</div>
					<div class="row">
				        <div class="well col-md-10">
				        	<label>Want to know how it works? <a href="#tutorial">Click here!</a></label>
						</div>
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>

</html>