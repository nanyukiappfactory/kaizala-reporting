<div class="modal fade" id="groupModal<?php echo $row->group_id; ?>" tabindex="-1" role="dialog"
    aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 img">
                        <img src="<?php echo $row->group_image_url; ?>" alt="" class="img-rounded" width="100">
                    </div>
                    <div class="col-md-6 details">
                        <blockquote>
                            <h5>
                                <?php echo $row->group_name; ?>
                            </h5>
                            <small><cite title="Source Title">
                                    <?php echo $row->group_type; ?> <i class="icon-map-marker"></i></cite></small>
                        </blockquote>
                        <p>
                            <?php echo strtotime($row->created_at); ?> <br>
                        </p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>