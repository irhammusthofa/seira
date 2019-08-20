<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Kategori
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Kategori</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/kategori/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Nama Kategori <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="kategori" name="kategori" type="text" class="form-control" placeholder="Nama Kategori"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Harga Member (1 Bln)</label>
                            <div class="col-sm-6">
                                <input id="member1" name="member1" type="number" class="form-control" placeholder="Harga Member 1 Bulan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Harga Member (2 Bln)</label>
                            <div class="col-sm-6">
                                <input id="member2" name="member2" type="number" class="form-control" placeholder="Harga Member 2 Bulan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Harga Non Member</label>
                            <div class="col-sm-6">
                                <input id="nmember" name="nmember" type="number" class="form-control" placeholder="Harga Non Member">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/kategori/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>