<?php

    $nameError = "";
    $errorMessage = null;
	
	// print_R($_GET);
	$product_id=$_GET['id'];
    if(isset($_POST['submit'])) {
        $connection = $conn;
        include_once('php/Subproduct.php');
        $item = $_POST;
        $isFormValid = true;
        if(!$item['name']) {
            $nameError = "Please enter valid name.";
            $isFormValid = false;
        }

        if($isFormValid) {
            $data = [
                'product_id' => $product_id,
                'name' => $item['name'],
                'product_price' => $item['product_price'],
                // 'printer_ip' => $item['printer_ip'],
                'status' => $item['status'],
            ];

            $sectionObj = new Subproduct($conn);
            if($sectionObj->create($data)) {
				$url=$site_url."/sub_product.php?p_id=".$product_id;
                redirectToUrl($url);
                exit;
            }

            $errorMessage = "Sub Product could not be saved. Please try again after some time.";
        }
    }
?>

<div class="container">
    <div class="row">
        <div class="well col-md-10">
            <?php if($errorMessage): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="panel price panel-red input-has-value">
                    <h2>Add New Sub Product <?php  echo $_SESSION['product_id'];?></h2>
                    <br><br>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" maxlength="35" class="form-control" required value="">
                        <?php if($nameError): ?>
                            <label id="name-error" class="error" for="name"><?php echo $nameError; ?></label>
                        <?php endif; ?>
                    </div>
                   
                    <div class="form-group">
                        <label>Product Price</label>  
                        <input type="text" name="product_price" class="form-control" required value="">
                    </div>

                    <div class="form-group form-check">
                        <label class="form-check-label" for="sectionStatus" style="padding-left: 0px;">Status</label>
                        <input type="checkbox" class="form-check-input" id="sectionStatus" checked name="status" style="margin-left:5px;">
                    </div>
                    <br>
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                   
                </div>
            </form>
        </div>
    </div>
</div>