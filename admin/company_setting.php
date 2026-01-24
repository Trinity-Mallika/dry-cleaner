<?php
include("../adminsession.php");
$pagename = 'company_setting.php';
$title = "Company Setting";
$module = "Company Setting";
$submodule = "Company Setting";
$btn_name = "Save";
$keyvalue = 1;
$tblname = "company_setting";
$tblpkey = "compid";
$imgpath = "uploaded/company/";

if (isset($_GET['action'])) {
    $action = $obj->test_input($_GET['action']);
} else {
    $action = "";
}

if (isset($_POST['submit'])) {

    $keyvalue   = intval($_POST['compid'] ?? 1);

    $comp_name  = $obj->test_input($_POST['comp_name'] ?? '');
    $gstno      = $obj->test_input($_POST['gstno'] ?? '');
    $mobile     = $obj->test_input($_POST['mobile'] ?? '');
    $mobile2    = $obj->test_input($_POST['mobile2'] ?? '');
    $email      = $obj->test_input($_POST['email'] ?? '');
    $address    = $obj->test_input($_POST['address'] ?? '');
    $term_cond  = $obj->test_input($_POST['term_cond'] ?? '');

    $imgname = "";
    if (!empty($_FILES['imgname']['name'])) {
        $ext = pathinfo($_FILES['imgname']['name'], PATHINFO_EXTENSION);
        $imgname = "logo_" . time() . "." . $ext;

        move_uploaded_file($_FILES['imgname']['tmp_name'], $imgpath . $imgname);
    } else {
        $imgname = $obj->getvalfield("company_setting", "imgname", "compid='$keyvalue'");
    }

    $form_data = array(
        "comp_name"   => $comp_name,
        "gstno"       => $gstno,
        "mobile"      => $mobile,
        "mobile2"     => $mobile2,
        "email"       => $email,
        "address"     => $address,
        "term_cond"   => $term_cond,
        "imgname"     => $imgname,
        "ipaddress"   => $ipaddress,
        "lastupdated" => $createdate
    );

    $where = array("compid" => $keyvalue);
    $obj->update_record("company_setting", $where, $form_data);

    echo "<script>location='$pagename?action=2'</script>";
    exit;
}


if ($keyvalue > 0) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $comp_name = $sqledit['comp_name'];
    $gstno = $sqledit['gstno'];
    $mobile = $sqledit['mobile'];
    $mobile2 = $sqledit['mobile2'];
    $address = $sqledit['address'];
    $email = $sqledit['email'];
    $term_cond = $sqledit['term_cond'];
    $imgname = $sqledit['imgname'];
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
</head>
<?php include('inc/css-link.php') ?>

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
                    <?= $module; ?>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row g-3">

                            <!-- Company Info -->
                            <div class="col-12">
                                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-0">Company Info</h6>
                            </div>

                            <div class="col-lg-6">
                                <label class="fw-semibold">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="comp_name" id="comp_name"
                                    class="form-control"
                                    placeholder="Enter Company Name"
                                    value="<?= $comp_name ?>">
                            </div>

                            <div class="col-lg-6">
                                <label class="fw-semibold">GST No.</label>
                                <input type="text" name="gstno" id="gstno"
                                    class="form-control"
                                    placeholder="Enter GST Number"
                                    value="<?= $gstno ?>">
                            </div>

                            <!-- Contact -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-0">Contact Details</h6>
                            </div>

                            <div class="col-lg-4">
                                <label class="fw-semibold">Mobile No. 1</label>
                                <input type="text" name="mobile" id="mobile"
                                    class="form-control"
                                    placeholder="Enter Mobile No. 1"
                                    value="<?= $mobile ?>">
                            </div>

                            <div class="col-lg-4">
                                <label class="fw-semibold">Mobile No. 2</label>
                                <input type="text" name="mobile2" id="mobile2"
                                    class="form-control"
                                    placeholder="Enter Mobile No. 2"
                                    value="<?= $mobile2 ?>">
                            </div>

                            <div class="col-lg-4">
                                <label class="fw-semibold">Email ID</label>
                                <input type="email" name="email" id="email"
                                    class="form-control"
                                    placeholder="Enter Email"
                                    value="<?= $email ?>">
                            </div>

                            <!-- Branding -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-0">Branding</h6>
                            </div>

                            <div class="col-lg-6">
                                <label class="fw-semibold">Logo</label>
                                <input type="file" name="imgname" id="imgname" class="form-control">

                                <?php if (!empty($imgname)) { ?>
                                    <div class="d-flex align-items-center gap-3 mt-2 p-2 border rounded">
                                        <img src="<?= $imgpath . $imgname ?>" style="height:60px;width:auto;" class="border rounded p-1">
                                        <div>
                                            <div class="small text-muted">Current Logo</div>
                                            <div class="fw-semibold"><?= $imgname ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-lg-6">
                                <label class="fw-semibold">Address</label>
                                <textarea name="address" id="address"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter Address"><?= $address ?></textarea>
                            </div>

                            <!-- Legal -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-0">Terms & Conditions</h6>
                            </div>

                            <div class="col-12">
                                <textarea name="term_cond" id="term_cond"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter Terms & Conditions"><?= $term_cond ?></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                <input type="hidden" name="<?= $tblpkey; ?>" id="<?= $tblpkey; ?>" value="<?= $keyvalue; ?>">

                                <button type="submit"
                                    name="submit"
                                    onclick="return checkinputmaster('comp_name')"
                                    class="btn btn-success btn-sm fw-semibold px-4">
                                    <?= $btn_name; ?>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
<?php include('inc/js-link.php') ?>

</html>