<?php
    include_once('php/Subproduct.php');
    $subproductObj = new Subproduct($conn);
    $filters = [
        'product_id' => $_SESSION['product_id']
    ];
    $items = $subproductObj->get($filters);
	// print_R($_SESSION);
   $product_id=$_SESSION['product_id'];
    $flashMessage = "";

    $errorMsg = isset($_GET['error']) ? $_GET['error'] : null;
    $successMsg = isset($_GET['success']) ? $_GET['success'] : null;

    $alertClass = "";
    if($successMsg) {
        $flashMessage = $successMsg;
        $alertClass = 'success';
    } else if($errorMsg) {
        $flashMessage = $errorMsg;
        $alertClass = 'danger';
    }
?>
<div class="col s12">
    <?php if($flashMessage): ?>
        <div class="alert alert-<?php echo $alertClass; ?>" role="alert">
            <?php echo $flashMessage; ?>
        </div>
    <?php endif; ?>
    <div>
        <h3 class="heading-section">
            Sub Product  for <?php echo $_SESSION['product_name']; ?>
        </h3>
        <a href="<?php echo $site_url; ?>/sub_product.php?action=create&id=<?php echo $product_id;?>" class="add-btn btn btn-success">
            <i class="fa fa-plus"></i>&nbsp;Add New Sub Product
        </a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
              
                <th class="text-center">Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orderview-body">
            <?php if(!$items): ?>
                <tr>
                    <td colspan="6">
                        No record found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($items as $item): ?>
                    <?php
                        $id = $item['id'];
                    ?>
                    <tr>
                        <td><?php echo $item['id']?></td>
                        <td><?php echo $item['name']?></td>
                       
                        <td><?php echo $item['product_price'];?></td>
                        <td class="text-center">
                            <?php
                                $statusIcon = '<i class="fa fa-times"></i>';
                                if($item['status']) {
                                    $statusIcon = '<i class="fa fa-check"></i>';
                                }
                            ?>
                            <a href="<?php echo $site_url; ?>/sub_product.php?action=toggle-status&id=<?php echo $id; ?>">
                                <?php echo $statusIcon; ?>
                            </a>
                        </td>
                        <td>
                          
                            <span style="float: left;">&nbsp;</span>
                            <a href="<?php echo $site_url; ?>/sub_product.php?action=edit&id=<?php echo $id; ?>&product_id=<?php echo  $product_id;?>" 
                                class="btn btn-secondary" 
                                title="Edit Section"
                                style="padding:2px 7px;float: left;">

                                <i class="fa fa-edit"></i>
                            </a>
                            <span style="float: left;">&nbsp;</span>
                            <form action="<?php echo $site_url; ?>/sub_product.php?action=delete&id=<?php echo $id; ?>" 
                                onsubmit="return confirm('Do you really want to delete section?');"
                                style="float: left;" method="POST" onsubmit="">
                                
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $_SESSION['product_id']; ?>">
                                <button class="btn btn-danger" name="submit_delete" type="submit" style="padding:2px 7px;" title="Delete Sub Product">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>