<?php
include("../action.php");
$pagename = 'comment_master.php';
$title = "Comment Master";

$module = "Comment Master";

$submodule = "Comment Master";

$btn_name = "Save";

$keyvalue = 0;

$tblname = "comment_master";

$tblpkey = "comment_id";
$ipaddress = $obj->get_client_ip();
// $sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");
$sessionid = 1;
$createdate = date('Y-m-d H:i:s');

if (isset($_GET['comment_id']))
    $keyvalue = $_GET['comment_id'];
else
    $keyvalue = 0;
if (isset($_GET['action'])) {

    $action = $obj->test_input($_GET['action']);
} else {
    $action = "";
}

if (isset($_POST['submit'])) {
    $keyvalue = $obj->test_input($_POST['comment_id']);

    $comment = $obj->test_input($_POST['comment']);
    $count = $obj->getvalfield("$tblname", "count(*)", "comment='$comment' and comment_id!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "Duplicate";
    } else {
        if ($keyvalue == 0) {

            $form_data = array('comment' => $comment, 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid);
            $lastid = $obj->insert_record($tblname, $form_data);
            $action = 1;

            $process = "insert";
        } else {

            //update

            $form_data = array('comment' => $comment, 'ipaddress' => $ipaddress, 'lastupdated' => $createdate);

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
    $comment = $sqledit['comment'];
} else {
    $comment = "";
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
            <?php include('inc/alert.php');?>
            <div class="card">
                <div class="card-header bg-success-subtle fw-bold">
                    comment Master
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">comment <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="comment" id="comment" class="form-control" placeholder="Enter comment" value="<?php echo $comment ?>">
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
                                <input type="submit" onclick="return checkinputmaster('comment')" name="submit" class="btn btn-success btn-sm  fw-semibold" value="<?php echo $btn_name; ?>">
                                <input type="hidden" name="<?php echo $tblpkey; ?>" id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-success-subtle fw-bold">
                    comment Master List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>Sno.</th>
                                    <th>comment Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                $sql = $obj->executequery("select * from $tblname order by $tblpkey DESC ");

                                foreach ($sql as $key) {

                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>

                                        <td><?php echo $key['comment']; ?></td>

                                        <!-- <td>1</td>
                                    <td>Naveen</td>
                                    <td>12 Killo</td>
                                    <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Adipisci, sequi!</td> -->
                                        <td>
                                            <a href="<?php echo $pagename . "?" . $tblpkey . "=" . $key['comment_id']; ?>" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="" class="btn btn-outline-danger" onclick="funDel('<?php echo  $key['comment_id']; ?>');"><i class="bi bi-trash-fill"></i></a>


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