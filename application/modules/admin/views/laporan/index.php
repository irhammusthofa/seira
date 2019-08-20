<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Lap. Anggota</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>
    <div class="box">
        <div class="box-header with-border">

        <h3 class="box-title">Laporan Per Periode</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <?= form_open('admin/laporan/anggota',array('method'=>'get','target'=>'_blank')) ?>
        <div class="box-body table-responsive">
            <div class="col-md-6">
                <div class="form-group">
                	<label>Tanggal Mulai</label>
                	<input type="date" name="tgl1" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tgl2" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Cetak</button>
        </div>
        <?= form_close() ?>
    </div>
    <!-- /.box -->

</section>