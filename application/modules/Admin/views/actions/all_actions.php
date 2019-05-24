<?php
$table_row_contents = "";
$remove_dups = array();
if (count($action_cards) > 0) {
    $count = 1;
    foreach ($action_cards as $row) {
        if(!in_array($row->action_card_package_id, $remove_dups) || count($remove_dups) == 0){
            $table_row_contents .= "
            <tr>
                <td>" . $count++ . "</td>
                <td>" . $row->action_card_package_name . "
                    <button type='button' class='btn btn-warning btn-sm float-right' data-toggle='modal' data-target='#editPackageName" . $row->action_card_id . "'>
                        Edit
                    </button>
                </td>
                <td>
                    <a href=" . base_url() . "administration/all-responses/" . $row->action_card_id . " class='btn btn-success btn-sm'>Responses</a>
                </td>
            </tr>";
    
            $v_edit_data['action_package'] = $row->action_card_package_name;
            $v_edit_data['action_id'] = $row->action_card_id;
            $this->load->view('actions/edit_package_name', $v_edit_data);
            array_push($remove_dups, $row->action_card_package_id);
        }
    }
}
?>
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="d-lg-flex align-items-center justify-content-between">
            <div class="alert alert-dark" role="alert">
                Action Cards
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-actions/action_card_package/' . $order_method, 'Action Package'); ?>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $table_row_contents; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>