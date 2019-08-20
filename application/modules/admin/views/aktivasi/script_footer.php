<script>
var table;
$(document).ready(function() {
    $('.select2').select2();
    loadtable();
});

function loadtable() {
    //datatables
    table = $('#dtable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'columns': [{
                'width': '50px'
            },
            null,
            null,
            null,
            null,
            null,
        ],
        'bDestroy': true,
        'processing': true, //Feature control the processing indicator.\
        'serverSide': true, //Feature control DataTables' server-side processing mode.\
        'order': [], //Initial no order.

        // Load data for the table's content from an Ajax source
        'ajax': {
            'url': "<?= site_url('admin/aktivasi/ajax_list/') ?>",
            'type': "POST",
        },

        //Set column definition initialisation properties.
        'columnDefs': [{
            'targets': [0], //first column / numbering column
            'orderable': false, //set not orderable
        }, ],

    });
}


function aktivasi(param) {
    param = decodeURIComponent(param);
    param = JSON.parse(param);
    var id = param.id;
    var id_encode = param.id_encode;
    var nama = param.nama;

    $('#aktivasi_kode').html(id);
    $('#aktivasi_nama').html(nama);
    $('#btnAktivasi').attr('href', '<?= base_url() ?>admin/aktivasi/proses/' + id_encode);
    $('#modal-aktivasi-anggota').modal();
}
function tolak(param) {
    param = decodeURIComponent(param);
    param = JSON.parse(param);
    var id = param.id;
    var id_encode = param.id_encode;
    var nama = param.nama;

    $('#tolak_kode').html(id);
    $('#tolak_nama').html(nama);
    $('#frmTolak').attr('action', '<?= base_url() ?>admin/aktivasi/tolak/' + id_encode);
    $('#modal-tolak-anggota').modal();
}
</script>