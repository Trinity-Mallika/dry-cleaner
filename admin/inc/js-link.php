<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/flatpicker.js"></script>
<script src="assets/js/commonfun.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- datatable  start-->
<style>
    /* datatable css  start*/
    .dt-bootstrap5 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
        display: flex;
        flex-wrap: wrap;
    }

    .dataTables_length {
        width: 33%;
    }

    .dt-buttons {
        width: 33%;
    }

    .dataTables_filter {
        width: 33%;
        display: flex;
        justify-content: right;
    }

    .dataTables_info {
        flex: 1 0 0%;
    }

    .bg-li-menu {
        background: #198754;
        border: 0px;
        padding: 2px 10px 3px;
        font-size: 14px;
        margin: 6px;
        border-radius: 6px;
    }
</style>

<script src="assets/datatable-header-js/jquery.dataTables.min.js"></script>
<script src="assets/datatable-header-js/dataTables.bootstrap5.min.js"></script>
<script src="assets/datatable-header-js/dataTables.buttons.min.js"></script>
<script src="assets/datatable-header-js/buttons.bootstrap5.min.js"></script>
<script src="assets/datatable-header-js/jszip.min.js"></script>
<script src="assets/datatable-header-js/pdfmake.min.js"></script>
<script src="assets/datatable-header-js/vfs_fonts.js"></script>
<script src="assets/datatable-header-js/buttons.html5.min.js"></script>
<script src="assets/datatable-header-js/buttons.print.min.js"></script>
<script src="assets/datatable-header-js/buttons.colVis.min.js"></script>
<script src="assets/select-2/select2.js"></script>

<script>
    // new DataTable('#example');
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'lBfrtip',
            buttons: [

                {
                    extend: 'excel',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    footer: true,
                },
                {
                    extend: 'print',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    footer: true,
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    footer: true,

                },
            ],


            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],


        });

    });
</script>