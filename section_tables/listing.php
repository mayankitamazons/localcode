<?php
    include_once('php/SectionTable.php');
    $sectionObj = new SectionTable($conn);
    $filters = [];

    $sectionId = null;
    if(isset($_GET['section_id']) && !empty($_GET['section_id'])) {
        $filters['section_id'] = $_GET['section_id'];
        $sectionId = $_GET['section_id'];
    }
    $items = $sectionObj->get($filters);

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
            Tables
        </h3>
        <a href="<?php echo $site_url; ?>/section_tables.php?action=create&requested_section_id=<?php echo $sectionId; ?>" class="add-btn btn btn-success">
            <i class="fa fa-plus"></i>&nbsp;Add New Table
        </a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Section</th>
                <th>Name</th>
                <th>Description</th>
                <th class="text-center">Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orderview-body">
            <?php if(!$items): ?>
                <tr>
                    <td colspan="6" class="text-center">
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
                        <td><?php echo $item['section_name'] ?: "--"; ?></td>
                        <td><?php echo $item['name']?></td>
                        <td><?php echo $item['description'] ?: "--"; ?></td>
                        <td class="text-center">
                            <?php
                                $statusIcon = '<i class="fa fa-times"></i>';
                                if($item['status']) {
                                    $statusIcon = '<i class="fa fa-check"></i>';
                                }
                            ?>
                            <a href="<?php echo $site_url; ?>/section_tables.php?action=toggle-status&id=<?php echo $id; ?>&requested_section_id=<?php echo $sectionId; ?>">
                                <?php echo $statusIcon; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo $site_url; ?>/section_tables.php?action=edit&id=<?php echo $id; ?>&requested_section_id=<?php echo $sectionId; ?>" 
                                class="btn btn-secondary" 
                                style="padding:2px 7px;float: left;">

                                <i class="fa fa-edit"></i>
                            </a>
                            
                            <span style="float: left;">&nbsp;</span>
                            <form action="<?php echo $site_url; ?>/section_tables.php?action=delete&id=<?php echo $id; ?>&requested_section_id=<?php echo $sectionId; ?>" 
                                onsubmit="return confirm('Do you really want to delete table?');"
                                style="float: left;" method="POST" onsubmit="">
                                
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button class="btn btn-danger" name="submit_delete" type="submit" style="padding:2px 7px;"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>