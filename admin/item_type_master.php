<?php
include("../adminsession.php");
$pagename = 'item_type_master.php';
$title = "Item Type Master";
$module = "Item Type Master";
$submodule = "Item Type Master";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "item_type_master";
$tblpkey = "item_type_master_id";

if (isset($_GET['item_type_master_id']))
    $keyvalue = $_GET['item_type_master_id'];
else
    $keyvalue = 0;
if (isset($_GET['action'])) {

    $action = $obj->test_input($_GET['action']);
} else {
    $action = "";
}

if (isset($_POST['submit'])) {
    $keyvalue = $obj->test_input($_POST['item_type_master_id']);

    $item_type_master_name = $obj->test_input($_POST['item_type_master_name']);
    $item_id = $obj->test_input($_POST['item_id']);
    $count = $obj->getvalfield(
        $tblname,
        "count(*)",
        "item_id='$item_id'
     AND item_type_master_name='$item_type_master_name'
     AND item_type_master_id!='$keyvalue'"
    );

    if ($count > 0) {
        $action = 4;
        $process = "Duplicate";
    } else {
        if ($keyvalue == 0) {

            $form_data = array('item_type_master_name' => $item_type_master_name, 'item_id' => $item_id, 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid);
            $lastid = $obj->insert_record($tblname, $form_data);
            $action = 1;

            $process = "insert";
        } else {

            //update

            $form_data = array('item_type_master_name' => $item_type_master_name, 'item_id' => $item_id, 'ipaddress' => $ipaddress, 'lastupdated' => $createdate);

            $where = array($tblpkey => $keyvalue);

            $obj->update_record($tblname, $where, $form_data);


            $action = 2;

            $process = "updated";
        }
    }
    // die;
    echo "<script>location='$pagename?action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {

    $btn_name = "Update";

    $where = array($tblpkey => $keyvalue);

    $sqledit = $obj->select_record($tblname, $where);
    $item_type_master_name = $sqledit['item_type_master_name'];
    $item_id = $sqledit['item_id'];
} else {
    $item_type_master_name = "";

    $item_id = $obj->getvalfield(
        $tblname,
        "item_id",
        "1=1 order by item_type_master_id desc limit 0,1"
    );
}




?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>

</head>
<?php include('inc/css-link.php') ?>
<!-- Bootstrap Icons (PUT THIS HERE) -->

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-5">
            <?php include('inc/alert.php'); ?>
            <div class="card">
                <div class="card-header bg-success-subtle fw-bold">
                    Item Type Master
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <strong><label>Item Name <span class="text-danger">*</span></label></strong>
                                <select name="item_id" id="item_id" class="form-control chosen-select">
                                    <option value="">Select Item Type</option>
                                    <?php
                                    $item = $obj->executequery("SELECT * FROM item_master ORDER BY item_name ASC");

                                    foreach ($item as $i) { ?>
                                        <option value="<?php echo $i['item_id']; ?>"><?php echo $i['item_name']; ?></option>
                                    <?php } ?>
                                </select>
                                <script>
                                    document.getElementById('item_id').value = '<?php echo $item_id ?>';
                                </script>
                            </div>
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Item Type <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="item_type_master_name" id="item_type_master_name" class="form-control" placeholder="Enter Item" value="<?php echo $item_type_master_name ?>">
                            </div>


                            <!-- <div class="col-lg-3 mt-4">
                            <a href="dashboard.php" class="btn btn-success btn-sm  fw-semibold">
                                Submit
                            </a>
                            <a href="<?= $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold">
                                Reset
                            </a>
                        </div> -->
                            <div class="col-lg-3 mt-4">

                                <input type="submit" onclick="return checkinputmaster('item_id,item_type_master_name')" name="submit" class="btn btn-success btn-sm  fw-semibold" value="<?php echo $btn_name; ?>">
                                <input type="hidden" name="<?php echo $tblpkey; ?>" id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-success-subtle fw-bold">
                    Item Type Master List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>Sno.</th>
                                    <th>Item Name</th>
                                    <th>Item Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                // id 1 is for laundry by weight
                                $sql = $obj->executequery("select * from $tblname where $tblpkey > 1 order by $tblpkey DESC ");

                                foreach ($sql as $key) {
                                    $item_id = $key['item_id'];
                                    $item_name = $obj->getvalfield("item_master", "item_name", "item_id='$item_id'");


                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>

                                        <td><?php echo $item_name; ?></td>
                                        <td><?php echo $key['item_type_master_name']; ?></td>
                                        <td>
                                            <a href="<?php echo $pagename . "?" . $tblpkey . "=" . $key['item_type_master_id']; ?>" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i></a>

                                            <a href="" class="btn btn-outline-danger" onclick="funDel('<?php echo  $key['item_type_master_id']; ?>');"><i class="bi bi-trash-fill"></i></a>

                                            <button class="btn btn-success btn-sm"
                                                onclick="openAddServiceModal('<?php echo $key['item_type_master_id']; ?>', '<?php echo htmlspecialchars($key['item_type_master_name'], ENT_QUOTES); ?>')">
                                                + Add Service
                                            </button>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('inc/js-link.php') ?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="item_type_master_m">
                    <input type="hidden" id="add_service_id">
                    <div class="row">
                        <div class="col-5">

                            <label for="">Service</label>
                            <select type="text" class="form-select" name="item_service_id" id="item_service_id">
                                <option value="">Select Service </option>
                                <?php
                                $item = $obj->executequery("SELECT * FROM item_service");

                                foreach ($item as $i) { ?>
                                    <option value="<?php echo $i['item_service_id']; ?>"><?php echo $i['item_sname']; ?></option>
                                <?php } ?>
                            </select>
                            <script>
                                document.getElementById('item_service_id').value = '<?php echo $item_service_id ?>';
                            </script>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="">Rate</label>
                            <input type="number" name="rate" id="rate" class="form-control">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success btn-sm mt-4 w-100" onclick="saveService()">
                                Add
                            </button>
                        </div>
                    </div>
                </form>
                <div id="fetch_typeService" class="m-2"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function openAddServiceModal(item_type_master_id, item_type_master_name) {
        $('#item_type_master_m').val(item_type_master_id);
        $('#exampleModalLabel').html("Add Service For " + item_type_master_name);
        $('#exampleModal').modal('show');
        fetch_typeService(item_type_master_id);
    }

    function saveService() {
        var item_type_master_id = $('#item_type_master_m').val();
        var item_service_id = $('#item_service_id').val().trim();
        var rate = $('#rate').val().trim();
        var add_service_id = $('#add_service_id').val();

        if (item_type_master_id === "" || item_service_id === "") {
            alert("Please enter Service name.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "save_service.php",
            data: {
                item_type_master_id: item_type_master_id,
                add_service_id: add_service_id,
                rate: rate,
                item_service_id: item_service_id
            },
            success: function(response) {
                if (response == 1) {
                    alert("service saved successfully!");
                } else if (response == 2) {
                    alert("service updated successfully!");
                } else if (response == 4) {
                    alert("Duplicate service name!");
                    return;
                }

                $('#item_service_id').val('');
                $('#rate').val('');
                $('#add_service_id').val('');
                $('#btn_package').html('Save');
                fetch_typeService(item_type_master_id);
            },
            error: function() {
                alert("Error saving service — check console or network.");
            }
        });
    }

    function fetch_typeService(item_type_master_id) {
        $.ajax({
            type: 'POST',
            url: 'fetch_typeService.php',
            data: {
                item_type_master_id: item_type_master_id
            },
            success: function(data) {
                $('#fetch_typeService').html(data);
            }
        });
    }



    function deleteservice(add_service_id, item_type_master_id) {
        if (confirm("Are you sure you want to delete this service?")) {
            $.ajax({
                type: "POST",
                url: "deleteservice.php",
                data: {
                    add_service_id: add_service_id
                },
                success: function(res) {
                    alert("service deleted successfully!");
                    fetch_typeService(item_type_master_id);
                },
                error: function() {
                    alert("Error deleting service!");
                }
            });
        }
    }





    function funDel(id) {

        // alert(id);

        tblname = '<?php echo $tblname; ?>';

        tblpkey = '<?php echo $tblpkey; ?>';

        pagename = '<?php echo $pagename; ?>';

        submodule = '<?php echo $submodule; ?>';

        module = '<?php echo $module; ?>';


        //alert(module);

        if (confirm("Are you sure! You want to delete this record.")) {

            jQuery.ajax({

                type: 'POST',

                url: 'ajax/delete_master.php',

                data: 'id=' + id +
                    '&tblname=' + tblname +
                    '&tblpkey=' + tblpkey +
                    '&submodule=' + submodule +
                    '&pagename=' + pagename +
                    '&module=' + module,


                dataType: 'html',

                success: function(data) {

                    //alert(data);

                    location = '<?php echo $pagename . "?action=3"; ?>';

                }

            }); //ajax close

        } //confirm close

    } //fun close

    $(document).ready(function() {
        $('#example').DataTable();
        $(".chosen-select").select2({
            width: '100%'
        });
    });
</script>

</html>