<?php
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    include_once('php/Subproduct.php');
    $sectionObj = new Subproduct($conn);
    $nameError = "";
    $errorMessage = null;
	$product_id=$_GET['product_id'];
    if(isset($_POST['submit']) && isset($_POST['_method']) && $_POST['_method'] == 'PUT') {

        $postData = $_POST;
        $isFormValid = true;
        if(!$postData['name']) {
            $nameError = "Please enter valid name.";
            $isFormValid = false;
        }

        if($isFormValid) {
            $data = [
                'name' => $postData['name'],
                'product_price' => $postData['product_price'],
              
                'status' => $postData['status'],
            ];

            $sectionObj = new Subproduct($conn);   
            if($sectionObj->update($id, $data)) {
				$url=$site_url."/sub_product.php?p_id=".$product_id."&success=Sub Product is updated successfully.";
                redirectToUrl($url);   
                exit;
            }

            $errorMessage = "SubProduct could not be saved. Please try again after some time.";
        }
    }


    $item = $sectionObj->findById($id);
    if(!$id || !$item) {     
			$url=$site_url."/sub_product.php?p_id=".$product_id."&error=Invalid Request ID";
        redirectToUrl($url);
        exit;
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
            <form method="post" action="<?php echo $site_url;?>/sub_product.php?action=edit&id=<?php echo $id?>&product_id=<?php echo $_GET['product_id']; ?>">  
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="panel price panel-red input-has-value">
                    <h2>Edit Sub Product</h2>
                    <br><br>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" maxlength="35" class="form-control" value="<?php echo $item['name']; ?>">
                        <?php if($nameError): ?>
                            <label id="name-error" class="error" for="name"><?php echo $nameError; ?></label>
                        <?php endif; ?>
                    </div>
                   
                    <div class="form-group">
                        <label>Product Price</label>
                        <input type="text" name="product_price" class="form-control" value="<?php echo $item['product_price']; ?>">
                    </div>

                    <div class="form-group form-check">
                        <label class="form-check-label" for="sectionStatus" style="padding-left: 0px;">Status</label>
                        <input type="checkbox" class="form-check-input" id="sectionStatus" name="status" style="margin-left:5px;" <?php echo $item['status'] ? 'checked' : ''; ?>>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    <a href="sub_product.php?p_id=<?php echo  $product_id;?>" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>