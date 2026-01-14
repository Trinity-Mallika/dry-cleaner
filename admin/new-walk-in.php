<?php include("../adminsession.php");
$pagename = "new-walk-in.php";
$title = "Walk In Order";
$module = "Walk In Order";
$submodule = "Walk In Order";
$btn_name = "Save";
$tblname = "orders";
$tblpkey = "order_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {
    $mobile_no = $obj->test_input($_POST['mobile_no']);
    $cust_name = $obj->test_input($_POST['cust_name']);
    $delivery_date = $obj->test_input($_POST['delivery_date']);
    $delivery_slot = $obj->test_input($_POST['delivery_slot']);
    $coupon_master_id = $obj->test_input($_POST['coupon_master_id']);
    $gross_total = $obj->test_input($_POST['gross_total']);
    $discount_amt = $obj->test_input($_POST['discount_amt']);
    $final_total = $obj->test_input($_POST['final_total']);
    $coupon_percent = $obj->test_input($_POST['coupon_percent']);
    $total_count = $obj->test_input($_POST['total_count']);

    if ($keyvalue == 0) {
        // customer entry
        $customer_id = $obj->insert_record_lastid("m_customer", ['customer_name' => $cust_name, "mobile" => $mobile_no, "address" => $address, "ipaddress" => $ipaddress, "createdby" => $loginid, "createdate" => $createdate]);

        $lastid = $obj->insert_record_lastid("orders", ['customer_id' => $customer_id, "customer_name" => $cust_name, "mobile" => $mobile_no, "delivery_date" => $delivery_date, "delivery_slot" => $delivery_slot, "gross_total" => $gross_total, "discount_amt" => $discount_amt, "final_total" => $final_total, "coupon_master_id" => $coupon_master_id, "coupon_percent" => $coupon_percent, "express_amount" => $express_amount, "total_count" => $total_count, "ipaddress" => $ipaddress, "createdby" => $loginid, "createdate" => $createdate, "createtime" => date("H:i:s"), "sessionid" => $sessionid]);
        $obj->update_record("order_item", ['createdby' => $loginid, "order_id" => 0], ["order_id" => $lastid]);
        $action = 1;
    }
    echo "<script>location='$pagename?action=$action'<script>";
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
    <style>
        .count-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .count-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
            line-height: 5px;
        }
    </style>
</head>
<?php include('inc/css-link.php') ?>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-5 mb-5">
            <?php include('inc/alert.php'); ?>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card card-body rounded-1" id="fetch_data">


                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card card-body rounded-1" id="show_products">

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- modal coupon Start -->
    <div class="modal fade" id="coupon" tabindex="-1" aria-labelledby="couponLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute w-25">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="couponLabel">Offers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 300px;" id="fetch_discounts">

                </div>
            </div>
        </div>
    </div>
    <!-- modal coupon End -->

    <!-- Add Garment Details Start -->
    <div class="modal fade" id="garment-details" tabindex="-1" aria-labelledby="garment-detailsLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute">
            <div class="modal-content" id="fetch_garment">

            </div>
        </div>
    </div>
    <!-- Add Garment Details End -->



    <!-- modal add-product Start -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute" style="width: 30% !important;">
            <div class="modal-content" id="modal-data">

            </div>
        </div>
    </div>
    <!-- modal add-product End -->

</body>
<?php include('inc/js-link.php') ?>

<script>
    // for qty plus minus
    document.addEventListener('click', e => {
        const btn = e.target.closest('.count-btn');
        if (!btn) return;

        const box = btn.closest('.count-box');

        const qtyEl = box.querySelector('.qty-value');
        let qty = parseInt(qtyEl.value || qtyEl.textContent, 10) || 1;


        if (btn.dataset.action === 'plus') qty++;
        if (btn.dataset.action === 'minus' && qty > 1) qty--;

        qtyEl.value = qty;
    });

    $(document).ready(function() {
        show_products();
        fetch_details();
        show_discounts();
    });

    let appliedCoupon = null;

    // total calculaton
    function recalculateTotals() {

        if (!window.ORDER_SUMMARY) {
            console.warn('ORDER_SUMMARY missing');
            return;
        }

        const gross = Number(ORDER_SUMMARY.gross) || 0;
        const count = Math.max(0, Number(ORDER_SUMMARY.count) || 0);

        let discount = 0;
        if (appliedCoupon?.percent) {
            discount = (gross * appliedCoupon.percent) / 100;
        }

        const finalTotal = Math.max(0, gross - discount);

        document.getElementById('grossTotal').textContent = gross.toFixed(2);
        document.getElementById('discountAmount').textContent = discount.toFixed(2);
        document.getElementById('finalTotal').textContent = finalTotal.toFixed(2);

        document.getElementById('total_count').value = count;
        document.getElementById('gross_total').value = gross.toFixed(2);
        document.getElementById('discount_amt').value = discount.toFixed(2);
        document.getElementById('final_total').value = finalTotal.toFixed(2);

        document.querySelectorAll('[data-total-count]')
            .forEach(el => el.textContent = `${count} pc`);
    }

    function loadProductsByAlpha(alpha) {
        show_products(alpha);
    }

    // for loading discounts
    function show_discounts() {
        $.ajax({
            type: 'POST',
            url: 'ajax/get_discounts.php',
            data: {
                id: 1,
            },
            dataType: 'html',
            success: function(data) {
                $('#fetch_discounts').html(data);
            }
        });
    }

    // for coupoun apply
    function applyCoupon(el) {

        appliedCoupon = {
            id: el.dataset.id,
            name: el.dataset.name,
            percent: parseFloat(el.dataset.amount)
        };

        $('#coupon').modal('hide');

        document.getElementById('addCouponBtn').classList.add('d-none');
        document.getElementById('appliedCouponBox').classList.remove('d-none');

        document.getElementById('appliedCouponName').innerText = appliedCoupon.name;
        document.getElementById('appliedCouponPercent').innerText = appliedCoupon.percent;
        document.getElementById('coupon_master_id').value = appliedCoupon.id;
        document.getElementById('coupon_percent').value = appliedCoupon.percent;

        recalculateTotals();

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Coupon applied',
            timer: 1200,
            showConfirmButton: false
        });
    }

    // delete coupoun
    function removeCoupon() {
        appliedCoupon = null;
        document.getElementById('appliedCouponBox').classList.add('d-none');
        document.getElementById('addCouponBtn').classList.remove('d-none');

        recalculateTotals();
    }

    // fetch products
    function show_products(alpha = 'ALL', id = "1") {

        const isLaundryByWeight = (String(id) === '2');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_products.php',
            data: {
                id: id,
                alpha: alpha
            },
            success: function(data) {
                if (isLaundryByWeight) {
                    $('#fetch_garment').html(data);
                } else {
                    // normal onscreen listing
                    $('#show_products').html(data);
                    initDefaultServices();
                }
            }
        });
    }

    function show_garment_modal() {

        $('#productModal').modal("hide");
        $('#garment-details').modal("show");

        show_products("ALL", "2");
    }

    function close_modal_garment() {

        $('#garment-details').modal("hide");
        $('#productModal').modal("show");
    }

    function handleLaundryAdd(item_id, btn) {
        // open product modal

        const card = btn.closest('.laundry-card');
        const activeType = card.querySelector('.item-type.active');
        const item_type_master_id = activeType ?
            activeType.dataset.type :
            '';
        $('#productModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal_laundry.php',
            data: {
                item_id: item_id,
                item_type_master_id: item_type_master_id,
                mode: 'add'
            },
            success: function(data) {
                $('#modal-data').html(data);

                get_service(
                    item_type_master_id,
                    item_id,
                    null,
                    'modal'
                );

                const btn = document.getElementById('addItemBtn1');
                if (btn) {
                    btn.classList.remove('disabled');
                    btn.removeAttribute('aria-disabled');
                }
            }
        });
    }

    function itemSearch() {
        const search = document
            .querySelector('.modal-header input[type="text"]')
            .value
            .toLowerCase();

        filterLaundryItems(search, window.__ACTIVE_ALPHA || 'ALL');
    }

    window.__ACTIVE_ALPHA = 'ALL';

    document.addEventListener('click', function(e) {

        const alphaEl = e.target.closest('.alpha-filter');
        if (!alphaEl) return;

        window.__ACTIVE_ALPHA = alphaEl.dataset.alpha;

        // update active styles
        document.querySelectorAll('.alpha-filter').forEach(el => {
            el.classList.remove('bg-success', 'text-white');
            el.classList.add('text-success');
        });

        alphaEl.classList.add('bg-success', 'text-white');

        const search = document
            .querySelector('.modal-header input[type="text"]')
            .value
            .toLowerCase();

        filterLaundryItems(search, window.__ACTIVE_ALPHA);
    });


    function filterLaundryItems(search, alpha) {

        document.querySelectorAll('.laundry-card').forEach(card => {

            const name = card.dataset.name;
            const first = card.dataset.alpha;

            const matchSearch = !search || name.includes(search);
            const matchAlpha = alpha === 'ALL' || first === alpha;

            card.style.display =
                (matchSearch && matchAlpha) ? '' : 'none';
        });
    }


    // edit a product
    function editOrderItem(order_item_id) {
        $('#productModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal.php',
            data: {
                mode: 'edit',
                order_item_id: order_item_id
            },
            success: function(data) {
                $('#modal-data').html(data);
            }
        });
    }

    // fetch save items
    function fetch_details() {
        $.ajax({
            type: 'POST',
            url: 'ajax/fetch_data.php',
            data: {
                keyvalue: '<?= $keyvalue ?>'
            },
            dataType: 'html',
            success: function(data) {
                $('#fetch_data').html(data);
                $('#mobile_no').focus();
                recalculateTotals();
                // Home Delivery toggle
                document.getElementById('home_delivery').addEventListener('change', function() {
                    const btn = document.getElementById('addAddressBtn');
                    document.getElementById('is_home_delivery').value = this.checked ? 1 : 0;

                    if (this.checked) {
                        btn.classList.remove('d-none');
                    } else {
                        btn.classList.add('d-none');
                    }
                });

                // Express Delivery toggle
                document.getElementById('express_delivery').addEventListener('change', function() {
                    const sel = document.getElementById('express_charge');
                    document.getElementById('is_express_delivery').value = this.checked ? 1 : 0;

                    if (this.checked) {
                        sel.classList.remove('d-none');
                    } else {
                        sel.classList.add('d-none');
                        sel.value = '0';
                        document.getElementById('express_percent').value = 0;
                    }
                });

                // Express charge change
                document.getElementById('express_charge').addEventListener('change', function() {
                    document.getElementById('express_percent').value = this.value;
                });

            }
        });
    }

    function find_cust(mobile_no) {

        if (!mobile_no || mobile_no.length !== 10) {
            $('#cust_name')
                .val('')
                .prop('readonly', false);
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'ajax_getcust.php',
            data: {
                mobile_no: mobile_no
            },
            dataType: 'text',
            success: function(data) {
                data = data.trim();
                if (data === 'not_found') {
                    $('#cust_name')
                        .val('')
                        .prop('readonly', false)
                        .focus();

                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'info',
                        title: 'New customer',
                        timer: 1200,
                        showConfirmButton: false
                    });

                } else {
                    $('#cust_name')
                        .val(data)
                        .prop('readonly', true);
                }
            }
        });
    }



    // show product add modal
    function show_modal(item_id, btn) {
        const card = btn.closest('.item-card');
        const activeType = card.querySelector('.item-type.active');
        const item_type_master_id = activeType ?
            activeType.dataset.type :
            '';

        $('#productModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal.php',
            data: {
                item_id: item_id,
                item_type_master_id: item_type_master_id,
                mode: 'add'
            },
            dataType: 'html',
            success: function(data) {
                $('#modal-data').html(data);
                get_service(
                    item_type_master_id,
                    item_id,
                    null,
                    'modal'
                );

                const btn = document.getElementById('addItemBtn');
                if (btn) {
                    btn.classList.add('disabled');
                    btn.setAttribute('aria-disabled', 'true');
                }
                const qtyEl = document.querySelector('.qty-value');
                if (qtyEl) qtyEl.textContent = 1;
            }
        });
    }

    // show service on change of item 
    function get_service(item_type_master_id, item_id = '', el = null, target = 'card') {
        let container;
        if (target === 'modal') {
            container = document.getElementById('modal-services');
        } else if (el) {
            container = el
                .closest('.card-body')
                .querySelector('.item-service');
        }

        if (!container) return;

        $.ajax({
            type: 'POST',
            url: 'ajax/get_item_service.php',
            data: {
                item_type_master_id: item_type_master_id,
                item_id: item_id,
                context: target
            },
            success: function(data) {
                container.innerHTML = data;

                if (target === 'modal') {
                    restoreSelectedServices();
                    updateProcessAreas();
                    updateAddButtonState();
                }

            }
        });
    }

    // show selected services on edit
    function restoreSelectedServices() {
        if (!window.__EDIT_SELECTION__) return;

        const selectedIds = window.__EDIT_SELECTION__.map(s => String(s.id));
        document.querySelectorAll('#modal-services .service-badge')
            .forEach(el => {
                if (selectedIds.includes(el.dataset.id)) {

                    el.classList.add(
                        'bg-success',
                        'text-white',
                        'selected'
                    );

                    el.classList.remove(
                        'bg-dark-subtle',
                        'text-black'
                    );
                }
            });
    }


    function syncQty(el) {
        let val = parseInt(el.value, 10);

        if (isNaN(val) || val < 1) {
            el.value = 1;
        }
    }

    // check wash and press checkbox 
    function updateProcessAreasModal() {
        const modal = document.getElementById('productModal');
        if (!modal) return;

        let needsWashing = false;
        let needsPressing = false;

        modal.querySelectorAll('.service-badge.selected').forEach(el => {
            if (el.dataset.is_washing === '1') needsWashing = true;
            if (el.dataset.is_pressing === '1') needsPressing = true;
        });

        const washChk = modal.querySelector('#area_washing');
        const pressChk = modal.querySelector('#area_pressing');

        if (washChk) washChk.checked = needsWashing;
        if (pressChk) pressChk.checked = needsPressing;
    }

    // set btn status
    function updateAddButtonStateModal() {
        const modal = document.getElementById('productModal');
        if (!modal) return;

        const btn = modal.querySelector('#addItemBtn');
        if (!btn) return;

        const hasService =
            modal.querySelectorAll('.service-badge.selected').length > 0;

        if (hasService) {
            btn.classList.remove('disabled');
            btn.removeAttribute('aria-disabled');
        } else {
            btn.classList.add('disabled');
            btn.setAttribute('aria-disabled', 'true');
        }
    }

    // set classes based on selection
    function setActiveType(el) {
        const wrap = el.closest('.card-body');

        wrap.querySelectorAll('.item-type').forEach(badge => {
            badge.classList.remove('bg-success', 'text-white', 'active');
            badge.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        get_service(el.dataset.type, el.dataset.item, el);

    }

    // for searching items
    function itemSearch() {
        let search = document.getElementById('itemSearch').value.toLowerCase();
        document.querySelectorAll('.item-card').forEach(card => {
            card.style.display = card.dataset.name.includes(search) ?
                'block' :
                'none';
        });
    }

    // set the first one active
    function initDefaultServices() {

        document.querySelectorAll('.item-card').forEach(card => {
            const activeType = card.querySelector('.item-type.active');

            if (!activeType) return;

            get_service(
                activeType.dataset.type,
                activeType.dataset.item,
                activeType
            );
        });
    }


    function toggleService(el, item_id) {

        item_id = parseInt(item_id, 10);

        /* ================= ITEM ID = 2 (SINGLE SERVICE) ================= */
        if (item_id === 2) {

            // already selected → ignore
            if (el.classList.contains('selected')) return;

            // clear all services
            document.querySelectorAll('.service-badge').forEach(badge => {
                badge.classList.remove('selected', 'bg-success', 'text-white');
                badge.classList.add('bg-dark-subtle', 'text-black');
            });

            // select clicked
            el.classList.add('selected', 'bg-success', 'text-white');
            el.classList.remove('bg-dark-subtle', 'text-black');

            // 🔥 SHOW ADDONS
            const serviceName = el.textContent.split('[')[0].trim();

            document.getElementById('addonlabel').innerText =
                serviceName + ' + Addons';

            document.getElementById('addon-wrapper').classList.remove('d-none');

            // reset addons on service change
            document.querySelectorAll('.addon-check').forEach(chk => {
                chk.checked = false;
            });

        } else {
            /* ================= NORMAL MULTI SELECT ================= */
            el.classList.toggle('bg-success');
            el.classList.toggle('text-white');
            el.classList.toggle('bg-dark-subtle');
            el.classList.toggle('text-black');
            el.classList.toggle('selected');
        }

        updateProcessAreasModal();
        updateAddButtonStateModal();
    }

    function toggleServiceLaundry(el) {
        el.classList.toggle('bg-success');
        el.classList.toggle('text-white');
        el.classList.toggle('bg-dark-subtle');
        el.classList.toggle('text-black');
        el.classList.toggle('selected');
    }



    function updateProcessAreas() {

        let needsWashing = false;
        let needsPressing = false;

        document.querySelectorAll('.service-badge.selected').forEach(el => {

            if (el.dataset.is_washing === '1') {
                needsWashing = true;
            }
            if (el.dataset.is_pressing === '1') {
                needsPressing = true;
            }
        });

        const washChk = document.getElementById('area_washing');
        const pressChk = document.getElementById('area_pressing');

        // Washing
        washChk.checked = needsWashing;
        // Pressing
        pressChk.checked = needsPressing;
    }

    function updateAddButtonState() {

        const hasService =
            document.querySelectorAll('.service-badge.selected').length > 0;

        const btn = document.getElementById('addItemBtn');

        if (!btn) return;

        if (hasService) {
            btn.classList.remove('disabled');
            btn.removeAttribute('aria-disabled');
        } else {
            btn.classList.add('disabled');
            btn.setAttribute('aria-disabled', 'true');
        }
    }

    function collectModalData(item_id) {

        const modal = document.getElementById('productModal');

        const itemTypeEl = modal.querySelector('.modal-item-type.active');
        const item_type_master_id = itemTypeEl ? itemTypeEl.dataset.type : '';

        const qtyEl = modal.querySelector('.qty-value');
        const qty = qtyEl ? parseInt(qtyEl.value || qtyEl.textContent, 10) : 1;

        const areas = [];
        modal.querySelectorAll('.process-area:checked')
            .forEach(el => areas.push(el.value));

        /* ===================== LAUNDRY (ITEM ID = 2) ===================== */
        if (item_id === 2) {

            // single service
            const serviceEl = modal.querySelector(
                '#modal-services .service-badge.selected'
            );

            if (!serviceEl) {
                alert('Please select a service');
                return null;
            }

            const service = {
                id: serviceEl.dataset.id,
                rate: serviceEl.dataset.rate
            };

            // addons
            const addonContainer = document.getElementById('modal-addons');
            const addons = [];

            if (addonContainer) {
                addonContainer
                    .querySelectorAll('.addon-check:checked')
                    .forEach(chk => {
                        addons.push({
                            id: chk.value,
                            rate: chk.dataset.rate,
                            name: chk.dataset.name
                        });
                    });
            }


            return {
                item_type_master_id,
                qty, // KG
                services: [service],
                addons,
                areas
            };
        }

        /* ===================== NORMAL ITEMS ===================== */

        const services = [];
        modal.querySelectorAll('#modal-services .service-badge.selected')
            .forEach(el => {
                services.push({
                    id: el.dataset.id,
                    rate: el.dataset.rate
                });
            });

        const requirements = [];
        modal.querySelectorAll('#modal-requirements .modal-requirement.selected')
            .forEach(el => {
                requirements.push({
                    id: el.dataset.id,
                    rate: el.dataset.rate
                });
            });

        const comments = [];
        modal.querySelectorAll('#modal-comments .modal-comment.selected')
            .forEach(el => comments.push(el.dataset.id));

        return {
            item_type_master_id,
            qty,
            services,
            requirements,
            comments,
            areas
        };
    }


    function collectModalData1() {

        const modal = document.getElementById('productModal');
        const itemTypeEl = modal.querySelector('.modal-item-type.active');
        const item_type_master_id = itemTypeEl ? itemTypeEl.dataset.type : '';

        const qty = document.getElementById('lan_qty').value || 1;
        const comments = [];
        modal.querySelectorAll('#modal-comments .modal-comment.selected')
            .forEach(el => comments.push(el.dataset.id));

        return {
            item_type_master_id,
            qty,
            comments
        };
    }

    function setFinalBtn() {
        const btn = document.getElementById('submitBtn');
        const mobile = document.getElementById('mobile_no');
        const name = document.getElementById('cust_name');

        if (!btn || !mobile || !name) return;

        const mobileOk = mobile.value.trim().length === 10;
        const nameOk = name.value.trim().length > 0;

        btn.disabled = !(mobileOk && nameOk);
    }

    function saveItemDetails(item_id) {

        if (document.querySelectorAll('.service-badge.selected').length === 0) {
            alert('Please select at least one service');
            return;
        }

        const payload = collectModalData(item_id);

        $.ajax({
            type: 'POST',
            url: 'ajax/save_order_item.php',
            data: {
                item_id: item_id,
                data: payload
            },
            success: function(response) {
                console.log(response);
                if (response !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response || 'Something went wrong'
                    });
                    return;
                }


                Swal.fire({
                    icon: 'success',
                    title: 'Item Added',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#productModal').modal('hide');
                    fetch_details();

                });
            }
        });
    }

    function saveItemDetailsLaundry(item_id) {

        const payload = collectModalData1();
        console.log(payload);
        $.ajax({
            type: 'POST',
            url: 'ajax/save_order_item_laundry.php',
            data: {
                item_id: item_id,
                data: payload
            },
            success: function(response) {
                if (response !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response || 'Something went wrong'
                    });
                    return;
                }


                Swal.fire({
                    icon: 'success',
                    title: 'Item Added',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#productModal').modal('hide');
                    fetch_details();

                });
            }
        });
    }

    function updateItemDetails(order_item_id) {

        if (document.querySelectorAll('.service-badge.selected').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Select at least one service'
            });
            return;
        }

        const payload = collectModalData(item_id);

        $.ajax({
            type: 'POST',
            url: 'ajax/update_order_item.php',
            data: {
                order_item_id: order_item_id,
                data: payload
            },
            success: function(res) {
                if (res !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update failed',
                        text: res
                    });
                    return;
                }

                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Item updated',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#productModal').modal('hide');
                    fetch_details();
                });
            }
        });
    }


    function onlyDigits(el) {
        el.value = el.value.replace(/\D/g, '');
        if (el.value === '' || el.value === '0') {
            el.value = '1';
        }
    }

    // delete saved order item
    function deleteOrderItem(order_item_id) {
        $.ajax({
            type: 'POST',
            url: 'ajax/delete_master.php',
            data: {
                id: order_item_id,
                tblname: 'order_item',
                tblpkey: 'order_item_id'
            },
            success: function(res) {
                if (res !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete failed',
                        text: res || 'Unable to delete item'
                    });
                    return;
                }
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Item removed',
                    timer: 1200,
                    showConfirmButton: false
                });
                fetch_details(); // refresh order list
            }
        });
    }



    function setModalItemType(el) {

        document.querySelectorAll('.modal-item-type').forEach(b => {
            b.classList.remove('bg-success', 'text-white', 'active');
            b.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        const modal = document.getElementById('productModal');
        const itemId = modal.querySelector('#addItemBtn')
            ?.getAttribute('onclick')
            ?.match(/\d+/)?.[0];

        get_service(el.dataset.type, itemId, null, 'modal');

    }

    function setModalItemType1(el) {

        document.querySelectorAll('.modal-item-type').forEach(b => {
            b.classList.remove('bg-success', 'text-white', 'active');
            b.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        const modal = document.getElementById('productModal');
        const itemId = modal.querySelector('#addItemBtn1')
            ?.getAttribute('onclick')
            ?.match(/\d+/)?.[0];
    }

    function syncHiddenFields() {
        document.getElementById('gross_total').value = ORDER_SUMMARY.gross;
        document.getElementById('total_count').value = ORDER_SUMMARY.count;
        document.getElementById('final_total').value =
            document.getElementById('finalTotal').textContent;
    }
</script>



</html>