<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data Kategori Latihan</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <h4>ID Instrktur : <span id="id_instruktur"><?= $data['instruktur']->i_kode ?></span></h4>
    <h4>Nama Instrktur : <?= $data['instruktur']->i_nama ?></h4><br>
    <?= fs_show_alert() ?>
    <div class="box">
        <div class="box-header with-border">

        <h3 class="box-title">Data Kategori Latihan</h3>
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
                        <th>Action</th>
                        <th>Nama Kategori</th>
                        <th>Status</th>
                        <!-- <th>Status</th> -->
                    </tr>
                </thead>
            </table>
        </div>
        <div class="box-footer">
            <?= anchor('admin/instruktur/kategori/add/'.base64_encode($data['instruktur']->i_kode),'Tambah',array('class'=>'btn btn-primary')) ?>
        </div>
    </div>
    <!-- /.box -->

</section>