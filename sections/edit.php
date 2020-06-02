<?php
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    include_once('php/Section.php');
    $sectionObj = new Section($conn);
    $nameError = "";
    $errorMessage = null;
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
                'description' => $postData['description'],
                'printer_ip' => $postData['printer_ip'],
                'status' => $postData['status'],
            ];

            $sectionObj = new Section($conn);
            if($sectionObj->update($id, $data)) {
                redirectToUrl($site_url.'/sections.php?success=Section is updated successfully.');
                exit;
            }

            $errorMessage = "Section could not be saved. Please try again after some time.";
        }
    }


    $item = $sectionObj->findById($id);
    if(!$id || !$item) {
        redirectToUrl($site_url.'/sections.php?error=Invalid Request ID.');
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
            <form method="post" action="<?php echo $site_url;?>/sections.php?action=edit&id=<?php echo $id?>">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="panel price panel-red input-has-value">
                    <h2>Edit Secton</h2>
                    <br><br>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" maxlength="35" class="form-control" value="<?php echo $item['name']; ?>">
                        <?php if($nameError): ?>
                            <label id="name-error" class="error" for="name"><?php echo $nameError; ?></label>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="sectionDescription">Description</label>
                        <textarea class="form-control" id="sectionDescription" name="description" rows="3"><?php echo $item['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>printer IP</label>
                        <input type="text" name="printer_ip" class="form-control" value="<?php echo $item['printer_ip']; ?>">
                    </div>

                    <div class="form-group form-check">
                        <label class="form-check-label" for="sectionStatus" style="padding-left: 0px;">Status</label>
                        <input type="checkbox" class="form-check-input" id="sectionStatus" name="status" style="margin-left:5px;" <?php echo $item['status'] ? 'checked' : ''; ?>>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    <a href="/sections.php" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>