<?php
$table_row_contents = "";
if (count($action_cards) > 0) {
    $count = 1;
    foreach ($action_cards as $row) {
        $table_row_contents .= "
		<tr>
			<td>" . $count++ . "</td>
			<td>" . $row->action_card_package . "
				<button type='button' class='btn btn-warning btn-sm float-right' data-toggle='modal' data-target='#editPackageName" . $row->action_card_id . "'>
					Edit
				</button>
			</td>
			<td>" . $row->group_name . "</td>
			<td>" . date('d M Y H:i', strtotime($row->created_at)) . "</td>
			<td>
				<a href=" . base_url() . "administration/all-responses/" . $row->action_card_id . " class='btn btn-success btn-sm'>Responses</a>
			</td>
		</tr>";

        $v_edit_data['action_package'] = $row->action_card_package;
        $v_edit_data['action_id'] = $row->action_card_id;
        $this->load->view('actions/edit_package_name', $v_edit_data);
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
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-actions/group_name/' . $order_method, 'Group Name'); ?>
                        </th>
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-actions/created_at/' . $order_method, 'Created Date'); ?>
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