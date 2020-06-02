<?php
    $nameError = "";
    $errorMessage = null;
    if(isset($_POST['submit'])) {
        $connection = $conn;
        include_once('php/Section.php');
        $item = $_POST;
        $isFormValid = true;
        if(!$item['name']) {
            $nameError = "Please enter valid name.";
            $isFormValid = false;
        }

        if($isFormValid) {
            $data = [
                'user_id' => $_SESSION['login'],
                'name' => $item['name'],
                'description' => $item['description'],
                'printer_ip' => $item['printer_ip'],
                'status' => $item['status'],
            ];

            $sectionObj = new Section($conn);
            if($sectionObj->create($data)) {
                redirectToUrl($site_url.'/sections.php');
                exit;
            }

            $errorMessage = "Section could not be saved. Please try again after some time.";
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
                    <h2>Add New Secton</h2>
                    <br><br>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" maxlength="35" class="form-control" value="">
                        <?php if($nameError): ?>
                            <label id="name-error" class="error" for="name"><?php echo $nameError; ?></label>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="sectionDescription">Description</label>
                        <textarea class="form-control" id="sectionDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>printer IP</label>
                        <input type="text" name="printer_ip" class="form-control" value="">
                    </div>

                    <div class="form-group form-check">
                        <label class="form-check-label" for="sectionStatus" style="padding-left: 0px;">Status</label>
                        <input type="checkbox" class="form-check-input" id="sectionStatus" name="status" style="margin-left:5px;">
                    </div>
                    <br>
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    <a href="/sections.php" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>