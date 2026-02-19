<?php
include("../adminsession.php");
$pagename = 'item_master.php';
$title = "Item Master";
$module = "Item Master";
$submodule = "Item Master";
$btn_name = "Save";
$tblname = "item_master";
$tblpkey = "item_id";

if (isset($_GET['item_id']))
    $keyvalue = $_GET['item_id'];
else
    $keyvalue = 0;
if (isset($_GET['action'])) {

    $action = $obj->test_input($_GET['action']);
} else {
    $action = "";
}

if (isset($_POST['submit'])) {
    $keyvalue = $obj->test_input($_POST['item_id']);
    $item_name = $obj->test_input($_POST['item_name']);
    $item_in = $obj->test_input($_POST['item_in']);
    $hsncode = $obj->test_input($_POST['hsncode']);
    $count = $obj->getvalfield("$tblname", "count(*)", "item_name='$item_name' and item_id!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "Duplicate";
    } else {
        if ($keyvalue == 0) {

            $form_data = array('item_name' => $item_name, 'item_in' => $item_in, 'hsncode' => $hsncode, 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid);
            $lastid = $obj->insert_record($tblname, $form_data);
            $action = 1;

            $process = "insert";
        } else {

            //update

            $form_data = array('item_name' => $item_name, 'item_in' => $item_in, 'hsncode' => $hsncode, 'ipaddress' => $ipaddress, 'lastupdated' => $createdate);

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
    $item_name = $sqledit['item_name'];
    $item_in = $sqledit['item_in'];
    $hsncode = $sqledit['hsncode'];
} else {
    $item_in = "0";
    $item_name = "";
    $hsncode = "";
}

if (isset($_POST['ajitem_id'])) {
    $ajitem_id = $obj->test_input($_POST['ajitem_id']);
    $slno = 1;

    $comments = $obj->executequery("
        SELECT m.map_id, c.comment
        FROM item_comment_map m
        JOIN comment_master c 
            ON c.comment_id = m.comment_id
        WHERE m.item_id = '$ajitem_id'
        ORDER BY m.map_id DESC
    ");
?>

    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th width="60">Sno.</th>
                <th>Comment Name</th>
                <th width="80">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $c): ?>
                    <tr>
                        <td><?= $slno++ ?></td>
                        <td><?= htmlspecialchars($c['comment']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger"
                                onclick="deletecomment('<?= $c['map_id']; ?>','<?= $ajitem_id; ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No comments added
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
<?php
    exit;
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
                    Item Master
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Item Name <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="item_name" id="item_name" class="form-control" placeholder="Enter Item" value="<?php echo $item_name ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">HSN Code <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="hsncode" id="hsncode" class="form-control" placeholder="Enter HSN Code" value="<?php echo $hsncode ?>">
                            </div>
                            <div class="col-lg-3">
                                <label class="fw-bold">Item In</label><br>
                                <label class="me-3">
                                    <input type="radio"
                                        name="item_in"
                                        value="0"
                                        <?= ($item_in == 0) ? 'checked' : '' ?>>
                                    Piece
                                </label>
                                <label>
                                    <input type="radio"
                                        name="item_in"
                                        value="1"
                                        <?= ($item_in == 1) ? 'checked' : '' ?>>
                                    Pair
                                </label>
                            </div>

                            <div class="col-lg-3 mt-4">
                                <input type="submit" onclick="return checkinputmaster('item_name')" name="submit" class="btn btn-success btn-sm  fw-semibold" value="<?php echo $btn_name; ?>">
                                <input type="hidden" name="<?php echo $tblpkey; ?>" id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-success-subtle fw-bold">
                    Item Master List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>Sno.</th>
                                    <th>Item Name</th>
                                    <th>HSN Code</th>
                                    <th>Item In</th>
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
                                        <td><?php echo $key['item_name']; ?></td>
                                        <td><?php echo $key['hsncode']; ?></td>
                                        <td><?php echo ($key['item_in'] == 1) ? "Pair" : "Piece"; ?></td>
                                        <td>
                                            <a href="<?php echo $pagename . "?" . $tblpkey . "=" . $key['item_id']; ?>" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="" class="btn btn-outline-danger" onclick="funDel('<?php echo  $key['item_id']; ?>');"><i class="bi bi-trash-fill"></i></a>

                                            <button class="btn btn-success btn-sm"
                                                onclick="openAddRequireModal('<?php echo $key['item_id']; ?>', '<?php echo htmlspecialchars($key['item_name'], ENT_QUOTES); ?>')">
                                                + Add Requirement
                                            </button>

                                            <button class="btn btn-success btn-sm"
                                                onclick="openModal('<?php echo $key['item_id']; ?>', '<?php echo htmlspecialchars($key['item_name'], ENT_QUOTES); ?>')">
                                                + Add Comments
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Requirement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                            <label for="">Requirement</label>
                            <select type="text" class="form-select" name="requirement_id" id="requirement_id">
                                <option value="">Select Requirement </option>
                                <?php
                                $item = $obj->executequery("SELECT * FROM requirement_master ORDER BY requirement ASC");

                                foreach ($item as $i) { ?>
                                    <option value="<?php echo $i['requirement_id']; ?>"><?php echo $i['requirement']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="">Rate</label>
                            <input type="number" name="rate" id="rate" class="form-control">
                        </div>

                        <div class="col-2">
                            <input type="hidden" id="item_master_m">
                            <input type="hidden" id="add_requirement_id">
                            <button type="button" class="btn btn-success btn-sm mt-4 w-100" onclick="saveRequire()">
                                Add
                            </button>
                        </div>
                    </div>
                    <div id="fetch_require" class="m-2"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="commentModalLabel">Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <label for="">Comment</label>
                            <select class="form-select"
                                name="comment_id[]"
                                id="comment_id"
                                multiple>

                                <?php
                                $item = $obj->executequery("SELECT * FROM comment_master ORDER BY comment ASC");
                                foreach ($item as $i) { ?>
                                    <option value="<?php echo $i['comment_id']; ?>"><?php echo $i['comment']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="hidden" id="item_id_m">
                            <input type="hidden" id="map_id">
                            <button type="button" class="btn btn-success btn-sm mt-4 w-100" onclick="saveComment()">
                                Add
                            </button>
                        </div>
                    </div>

                    <div id="fetch_comment" class="m-2"></div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('inc/js-link.php') ?>
<script>
    function openAddRequireModal(item_id, item_name) {
        $('#item_master_m').val(item_id);
        $('#exampleModalLabel').html("Add Requirement For " + item_name);
        $('#exampleModal').modal('show');
        fetch_require(item_id);
    }

    function openModal(item_id, item_name) {
        $('#item_id_m').val(item_id);
        $('#commentModalLabel').html("Add Comments For " + item_name);
        $('#commentModal').modal('show');
        fetchItemComments(item_id);
    }

    function saveComment() {
        const item_id = $('#item_id_m').val();
        const comment_ids = $('#comment_id').val();

        if (!item_id) {
            alert('Item missing');
            return;
        }

        if (!comment_ids || comment_ids.length === 0) {
            alert('Please select at least one comment');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'save_item_comments.php',
            data: {
                item_id: item_id,
                comment_ids: comment_ids
            },
            success: function(res) {
                fetchItemComments(item_id);
                $('#comment_id').val([]).trigger('change');
            }
        });
    }

    function fetchItemComments(item_id) {

        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: {
                ajitem_id: item_id
            },
            success: function(data) {
                $('#fetch_comment').html(data);
            }
        });
    }



    function saveRequire() {
        var item_id = $('#item_master_m').val();
        var requirement_id = $('#requirement_id').val().trim();
        var rate = $('#rate').val().trim();
        var add_requirement_id = $('#add_requirement_id').val();

        if (item_id === "" || requirement_id === "") {
            alert("Please enter Requirement name.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "save_requirement.php",
            data: {
                item_id: item_id,
                add_requirement_id: add_requirement_id,
                rate: rate,
                requirement_id: requirement_id
            },
            success: function(response) {
                if (response == 1) {
                    alert("Requirement saved successfully!");
                } else if (response == 2) {
                    alert("Requirement updated successfully!");
                } else if (response == 4) {
                    alert("Duplicate Requirement name!");
                    return;
                }

                $('#requirement_id').val('');
                $('#rate').val('');
                $('#add_requirement_id').val('');
                $('#btn_package').html('Save');
                fetch_require(item_id);
            },
            error: function() {
                alert("Error saving service — check console or network.");
            }
        });
    }

    function fetch_require(item_id) {
        $.ajax({
            type: 'POST',
            url: 'fetch_require.php',
            data: {
                item_id: item_id
            },
            success: function(data) {
                $('#fetch_require').html(data);
            }
        });
    }

    function deleterequire(add_requirement_id, item_id) {
        if (confirm("Are you sure you want to delete this requirement?")) {
            $.ajax({
                type: "POST",
                url: "deleterequirement.php",
                data: {
                    add_requirement_id: add_requirement_id
                },
                success: function(res) {
                    alert("requirement deleted successfully!");
                    fetch_require(item_id);
                },
                error: function() {
                    alert("Error deleting requirement!");
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
    });
</script>

</html>