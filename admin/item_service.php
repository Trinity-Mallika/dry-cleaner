<?php
include("../adminsession.php");
$pagename = 'item_service.php';
$title = "Item Service";
$module = "Item Service";
$submodule = "Item Service";
$btn_name = "Save";
$tblname = "item_service";
$tblpkey = "item_service_id";
$keyvalue = isset($_GET['item_service_id']) ? $obj->test_input($_GET['item_service_id']) : 0;
$action = isset($_GET['action']) ? $obj->test_input($_GET['action']) : '';


if (isset($_POST['submit'])) {

    $is_pressing = isset($_POST['is_pressing']) ? $obj->test_input($_POST['is_pressing']) : 0;
    $is_washing = isset($_POST['is_washing']) ? $obj->test_input($_POST['is_washing']) : 0;
    $item_sname = $obj->test_input($_POST['item_sname']);

    $count = $obj->getvalfield("$tblname", "count(*)", "item_sname='$item_sname' and item_service_id!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "Duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data = array('item_sname' => $item_sname, 'is_pressing' => $is_pressing, "is_washing" => $is_washing, 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid, "session_id" => $sessionid);
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {

            //update
            $form_data = array('item_sname' => $item_sname, 'is_pressing' => $is_pressing, "is_washing" => $is_washing, 'ipaddress' => $ipaddress, 'lastupdated' => $createdate, 'createdby' => $loginid, "session_id" => $sessionid);
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
    }

    echo "<script>location='$pagename?action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $item_sname = $sqledit['item_sname'];
    $is_washing = $sqledit['is_washing'];
    $is_pressing = $sqledit['is_pressing'];
} else {
    $item_sname =  "";
    $is_washing = $is_pressing = "1";
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
                    Item Service
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Item Service Name <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="item_sname" id="item_sname" class="form-control" placeholder="Enter Item Service" value="<?php echo $item_sname ?>">
                            </div>
                            <div class="col-lg-2 mt-4">
                                <div class="form-check ">
                                    <label class="form-check-label" for="formCheck6">
                                        Washing Area
                                    </label>
                                    <input class="form-check-input" type="checkbox" id="is_washing" name="is_washing" value="1" <?= ($is_washing == 1) ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="col-lg-2 mt-4">
                                <div class="form-check ">
                                    <label class="form-check-label" for="formCheck6">
                                        Pressing Area
                                    </label>
                                    <input class="form-check-input" type="checkbox" id="is_pressing" name="is_pressing" value="1" <?= ($is_pressing == 1) ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="col-lg-3 mt-4">
                                <input type="submit" onclick="return checkinputmaster('item_sname')" name="submit" class="btn btn-success btn-sm  fw-semibold" value="<?php echo $btn_name; ?>">
                                <input type="hidden" name="<?php echo $tblpkey; ?>" id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-success-subtle fw-bold">
                    Item Service List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>Sno.</th>
                                    <th>Item Service Name</th>
                                    <th>Washing Area</th>
                                    <th>Pressing Area</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                // below 3 are set for laundry by weight
                                $sql = $obj->executequery("select * from $tblname where item_service_id > 3 order by $tblpkey DESC ");

                                foreach ($sql as $key) {

                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $key['item_sname']; ?></td>
                                        <td><?php echo ($key['is_washing'] == 1) ? "Yes" : "No"; ?></td>
                                        <td><?php echo ($key['is_pressing'] == 1) ? "Yes" : "No"; ?></td>
                                        <td>
                                            <a href="<?php echo $pagename . "?" . $tblpkey . "=" . $key['item_service_id']; ?>" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="" class="btn btn-outline-danger" onclick="funDel('<?php echo  $key['item_service_id']; ?>');"><i class="bi bi-trash-fill"></i></a>


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
<script>
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
    });
</script>

</html>