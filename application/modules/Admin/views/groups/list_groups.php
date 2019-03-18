<?php 
$tr_groups = "";
if ($kaiza_groups->num_rows() > 0) 
{
	$count = $counter + 1;
	foreach ($kaiza_groups->result() as $row) 
	{
		$group_name = preg_replace('/\s/', '-', $row->group_name);

		if($row->group_status == 1)
		{
			$message = "Are you Sure You want to Deactivate???";
			$td_group_status = "
				<a href='" . base_url() . "administration/deactivate-group/" . $row->group_id . "'
				class='btn btn-sm btn-warning' onclick='return confirm(\"" . $message . "\")'>
					<i class='fa fa-thumbs-down'></i>
				</a>
			";
		}
		else
		{
			$message = "Are you Sure You want to Activate???";
			$td_group_status = "
				<a href='" . base_url() . "administration/activate-group/" . $row->group_id . "' class='btn btn-sm btn-success' onclick='return confirm(\"" . $message . "\")'>
					<i class='fa fa-thumbs-up'></i>
				</a>
			";
		}

		$tr_groups .="
		<tr>
			<td>" . $count++ . "</td>
			<td>" . $row->group_name . "</td>
			<td>" . $row->group_type . "</td>
			<td>" .date('d M Y H:i', strtotime($row->created_at)) . "</td>
			<td>
				<button type='button' class='btn btn-danger btn-sm' data-toggle='modal'
					data-target='#groupActions" . $row->group_id . "'>
					Action Cards
				</button>
				<a href='" . base_url() . "administration/group-users/" . $group_name ."/" . $row->group_id . "' class='btn btn-secondary btn-sm'><i class='fa fa-users'></i></a>" .
					$td_group_status . "
			</td>
		</tr>
		";
		
		$s_group['group_name'] = $row->group_name;
		$s_group['group_id'] = $row->group_id;
		$s_group['group_unique_id'] = $row->group_unique_id;
		$this->load->view('groups/group_action_cards', $s_group);
	}
}
?>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <a href="<?php echo base_url(); ?>administration/fetch-groups"
                class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-3"><i
                    class="fa fa-download fa-sm text-white-50"></i> Fetch Groups</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-groups/group_name/' . $order_method, 'Group Name'); ?>
                        </th>
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-groups/group_type/' . $order_method, 'Group Type'); ?>
                        </th>
                        <th>
                            <?php echo anchor(base_url() . 'administration/all-groups/created_at/' . $order_method, 'Created Date'); ?>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tr_groups;?>
                </tbody>
            </table>
        </div>

        <p>
            <?php echo $links; ?>
        </p>
    </div>
</div>