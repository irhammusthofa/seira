<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Paket
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Paket</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/paket/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Anggota <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('anggota',$data['anggota'],'',array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Kategori <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('kategori',$data['kategori'],'',array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal Daftar </label>
                            <div class="col-sm-6">
                                <input id="tgl" name="tgl" type="date" class="form-control" placeholder="Tanggal daftar">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal Expired </label>
                            <div class="col-sm-6">
                                <input id="expired" name="expired" type="date" class="form-control" placeholder="Tanggal Expired">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/paket/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>