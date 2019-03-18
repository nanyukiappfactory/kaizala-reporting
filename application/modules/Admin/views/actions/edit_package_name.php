<div class="modal fade" id="editPackageName<?php echo $action_id;?>" tabindex="-1" role="dialog" aria-labelledby="editPackageNameLabel"
 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<?php echo form_open(base_url() . 'administration/edit-package-name/' . $action_id); ?>

		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPackageNameLabel">Edit Action Package</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<div class="text-center col-sm-12 colo-md-12">
                        Old PackageName: <h5><b><?php echo $action_package;?></b></h5>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<input type="text" class="form-control" id="new_package_name" name="new_package_name" placeholder="Enter new Package name" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
