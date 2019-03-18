<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->

<?php
if ($check == 'groups') 
{
	$search_params = $this->session->userdata('search_params');
    $Kaizala_groups = $groups->result();
    $arr = [];
    $group_name_options = "";
    $group_type_options = "";

    foreach ($Kaizala_groups as $row)
    {
        $group_name_options .= '<option value="'. $row->group_name . '">'. $row->group_name . '</option>';
        if (!in_array($row->group_type, $arr)) 
        {
            $group_type_options .= "<option value=". $row->group_type . ">". $row->group_type .	"</option>";
            array_push($arr, $row->group_type);
        }
    }

    echo form_open(base_url() . 'admin/group/search_groups', array("class" => "d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-3 mw-100 navbar-search col-md-7"));?>
    <div class="input-group col-md-12">
        <select class="form-control bg-light border-0 small custom-select2" id="group_name" name="group_name"
            aria-label="Search" aria-describedby="basic-addon2">
            <option value="">Select Group Name...</option>
            <?php echo $group_name_options;?>
        </select>
        <select class="form-control bg-light border-0 small custom-select2 ml-3" id="group_type" name="group_type"
            aria-label="Search" aria-describedby="basic-addon2">
            <option value="">Select Group Type..</option>
            <?php echo $group_type_options;?>
        </select>
        <div class="input-group-append">
            <button class="btn btn-primary ml-3" type="submit">
                Search
            </button>
            <?php if ($search_params) {?>
            <div class="col-auto my-1">
                <a href="<?php echo base_url(); ?>admin/group/close_search" class="btn btn-danger">
                    CancelSearch</a>
            </div>
            <?php }?>
        </div>
    </div>
    <?php echo form_close(); ?>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <?php echo form_open(base_url() . 'admin/group/search_groups', "class=form-inline mr-auto w-100 navbar-search"); ?>
                <div class="input-group">
                    <div class="form-row align-items-center">
                        <div class="input-group">
                            <div class="col-sm-4 my-1">
                                <select class="form-control bg-light border-0 small custom-select2" id="group_name"
                                    name="group_name" aria-label="Search" aria-describedby="basic-addon2">
                                    <option value="">Select Group Name..</option>
                                    <?php echo $group_name_options;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 my-1">
                            <div class="input-group">
                                <select class="form-control bg-light border-0 small custom-select2" id="group_type"
                                    name="group_type" aria-label="Search" aria-describedby="basic-addon2">
                                    <option value="">Select Group Type..</option>
                                    <?php echo $group_type_options;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto my-1">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        <?php if ($search_params) {?>
                        <div class="col-auto my-1">
                            <a href="<?php echo base_url(); ?>admin/group/close_search" class="btn btn-danger">
                                CancelSearch
                            </a>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </li>
        <?php }?>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-left shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>