<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><?php echo $page_header; ?></h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<th>
                            UserName
						</th>
						<th>
                            PhoneNumber
						</th>
						<th>
                            Role
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
    $count = 1;
    foreach ($users as $row) {
        ?>
					<tr>
						<td>
							<?php echo $count++ ?>
						</td>
						<td>
							<?php echo $row->user_name; ?>
						</td>
						<td>
							<?php echo $row->user_mobile_number; ?>
						</td>
						<td>
							<?php echo $row->user_role; ?>
						</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>
</div>
