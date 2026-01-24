<div class="offcanvas offcanvas-start z-3" data-bs-scroll="true" data-bs-backdrop="false" data-bs-keyboard="false"
    tabindex="-1" id="offcanvasScrolling" style="width: 270px;">

    <div class="offcanvas-body p-0 pb-4">

        <!-- USER -->
        <div class="d-flex gap-3 p-3 border-bottom">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                style="width:48px;height:48px">
                <i class="bi bi-person-fill fs-2"></i>
            </div>
            <div>
                <strong>Mrs. Tabassum Khan</strong><br>
                <small class="text-muted">FAB-CHHOTAPARA</small>
                <div class="text-success small">★★★★★</div>
            </div>
        </div>

        <!-- MENU -->
        <ul class="nav flex-column p-2 gap-1">

            <!-- HOME -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'dashboard.php') ? 'active' : ''; ?>"
                    href="dashboard.php">
                    <i class="bi bi-house-fill me-4 text-secondary"></i>
                    <small class="text-black">Home</small>
                </a>
            </li>

            <!-- MASTER -->
            <ul class="sidebar-menu list-unstyled">

                <li class="menu-item">

                    <button class="menu-link <?= ($pagename == 'company_setting.php' || $pagename == 'item_master.php' || $pagename == 'item_service.php' || $pagename == 'item_type_master.php' || $pagename == 'coupon_master.php' || $pagename == 'requirement_master.php' || $pagename == 'comment_master.php') ? 'active' : ''; ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#masterSubmenu"
                        aria-expanded="<?= ($pagename == 'company_setting.php' || $pagename == 'item_master.php') ? 'true' : 'false'; ?>">

                        <i class="bi bi-mortarboard-fill me-4 text-secondary"></i>
                        <small class="text-black ms-2">Master</small>
                        <i class="bi bi-chevron-down ms-auto arrow"></i>
                    </button>

                    <ul class="submenu collapse <?= ($pagename == 'company_setting.php' || $pagename == 'item_master.php' || $pagename == 'item_service.php' || $pagename == 'item_type_master.php' || $pagename == 'coupon_master.php' || $pagename == 'requirement_master.php' || $pagename == 'comment_master.php') ? 'show' : ''; ?>"
                        id="masterSubmenu">
                        <li>
                            <a href="company_setting.php"
                                class="<?= ($pagename == 'company_setting.php') ? 'active' : ''; ?>">
                                Company Setting
                            </a>
                        </li>
                        <li>
                            <a href="item_master.php"
                                class="<?= ($pagename == 'item_master.php') ? 'active' : ''; ?>">
                                Item Master
                            </a>
                        </li>
                        <li>
                            <a href="item_type_master.php"
                                class="<?= ($pagename == 'item_type_master.php') ? 'active' : ''; ?>">
                                Item Type Master
                            </a>
                        </li>
                        <li>
                            <a href="item_service.php"
                                class="<?= ($pagename == 'item_service.php') ? 'active' : ''; ?>">
                                Item Service Master
                            </a>
                        </li>
                        <li>
                            <a href="coupon_master.php"
                                class="<?= ($pagename == 'coupon_master.php') ? 'active' : ''; ?>">
                                Coupon Master
                            </a>
                        </li>
                        <li>
                            <a href="requirement_master.php"
                                class="<?= ($pagename == 'requirement_master.php') ? 'active' : ''; ?>">
                                Requirement Master
                            </a>
                        </li>
                        <li>
                            <a href="comment_master.php"
                                class="<?= ($pagename == 'comment_master.php') ? 'active' : ''; ?>">
                                Comment Master
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <!-- NEW WALK-IN -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'new-walk-in.php') ? 'active' : ''; ?>"
                    href="new-walk-in.php">
                    <i class="bi bi-person-walking me-4 text-secondary"></i>
                    <small class="text-black">New Walk-in</small>
                </a>
            </li>

            <!-- B2C -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'b2c-order.php') ? 'active' : ''; ?>"
                    href="b2c-order.php">
                    <i class="bi bi-cart4 me-4 text-secondary"></i>
                    <small class="text-black">B2C Order</small>
                </a>
            </li>

            <!-- SETTLEMENT -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'settle-orders.php') ? 'active' : ''; ?>"
                    href="settle-orders.php">
                    <i class="bi bi-check2-all me-4 text-secondary"></i>
                    <small class="text-black">Settlement</small>
                </a>
            </li>

            <!-- BILL -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'bill.php') ? 'active' : ''; ?>"
                    href="bill.php">
                    <i class="bi bi-receipt me-4 text-secondary"></i>
                    <small class="text-black">Bill</small>
                </a>
            </li>

            <!-- PAYMENT -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'payment.php') ? 'active' : ''; ?>"
                    href="payment.php">
                    <i class="bi bi-person-rolodex me-4 text-secondary"></i>
                    <small class="text-black">Payment</small>
                </a>
            </li>

            <!-- REPORT -->
            <li class="nav-item">
                <a class="nav-link <?= ($pagename == 'report.php') ? 'active' : ''; ?>"
                    href="report.php">
                    <i class="bi bi-journal-text me-4 text-secondary"></i>
                    <small class="text-black">Report</small>
                </a>
            </li>

            <hr>

            <!-- LOGOUT -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-box-arrow-right me-4 text-danger fs-4"></i>
                    <small class="text-black">Logout</small>
                </a>
            </li>

        </ul>

    </div>
</div>