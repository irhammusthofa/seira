<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data Jadwal</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">

            <h3 class="box-title">Jadwal Hari ini | <?= date('d-m-Y'); ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['jadwal-hari-ini'] as $item) { ?>
                            <tr>
                                <td><?= $item->ji_jam_mulai ?></td>
                                <td><?= $item->ji_jam_selesai ?></td>
                                <td><?= $item->k_kategori ?></td>
                                <td><?= $item->r_ruangan ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">

            <h3 class="box-title">Data Jadwal</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="dtable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                            <th>Kuota</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.box -->

</section>