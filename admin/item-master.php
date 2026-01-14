<?php $pagename = 'item-master.php' ?>

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
            <div class="card">
                <div class="card-header bg-success-subtle fw-bold">
                    Item Master
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="" class="fw-bold">Item Name</label>
                            <input type="text" class="form-control" placeholder="Enter Item">
                        </div>
                        <div class="col-lg-3">
                            <label for="" class="fw-bold">Item Select</label>
                            <select name="" id="" class="form-select">
                                <option value="">12</option>
                                <option value="">12</option>
                                <option value="">12</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="" class="fw-bold">Remark</label>
                            <textarea name="" id="" class="form-control" rows="1"></textarea>
                        </div>
                        <div class="col-lg-3 mt-4">
                            <a href="dashboard.php" class="btn btn-success btn-sm  fw-semibold">
                                Submit
                            </a>
                            <a href="<?= $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold">
                                Reset
                            </a>
                        </div>
                    </div>
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
                                    <th>Item Select</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Naveen</td>
                                    <td>12 Killo</td>
                                    <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Adipisci, sequi!</td>
                                    <td>
                                        <a href="" class="btn btn-outline-success"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="" class="btn btn-outline-danger"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('inc/js-link.php') ?>

</html>