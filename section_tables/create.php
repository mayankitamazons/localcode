<?php
    $nameError = "";
    $errorMessage = null;
    $requestedSectionId = isset($_GET['requested_section_id']) ? $_GET['requested_section_id'] : null;
    if(isset($_POST['submit'])) {

        $connection = $conn;
        include_once('php/SectionTable.php');
        $item = $_POST;
        $isFormValid = true;
        if(!$item['name']) {
            $nameError = "Please enter valid name.";
            $isFormValid = false;
        }

        if($isFormValid) {
            $status = false;
            if(isset($item['status']) && ($item['status'] == 'on' || $item['status'] == true)) {
                $status = true;
            }
            $data = [
                'section_id' => $item['section_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'status' => $status,
            ];

            $tableObj = new SectionTable($conn);
            if($tableObj->create($data)) {
                redirectToUrl($site_url.'/section_tables.php?section_id='.$requestedSectionId);
                exit;
            }

            $errorMessage = "Table could not be saved. Please try again after some time.";
        }
    }
    include_once('php/Section.php');
    $sectionObj = new Section($conn);
    $sectionFilters = [
        'user_id' => $_SESSION['login'],
        'status' => true
    ];
    $sectionsList = $sectionObj->getList($sectionFilters);
?>

<div class="container">
    <div class="row">
        <div class="well col-md-10">
            <?php if($errorMessage): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="panel price panel-red input-has-value">
                    <h2>Add New Table</h2>
                    <br><br>
                    <?php if(!$requestedSectionId):?>
                        <div class="form-group">
                            <label>Section</label>
                            <select class="form-control" name="section_id">
                                <?php foreach($sectionsList as $id => $name): ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="section_id" value="<?php echo $requestedSectionId; ?>">
                        <input type="hidden" name="requested_section_id" value="<?php echo $requestedSectionId; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Table Number</label>
                        <input type="text" name="name" maxlength="35" class="form-control" value="">
                        <?php if($nameError): ?>
                            <label id="name-error" class="error" for="name"><?php echo $nameError; ?></label>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="sectionDescription">Description</label>
                        <textarea class="form-control" id="sectionDescription" name="description" rows="3"></textarea>
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