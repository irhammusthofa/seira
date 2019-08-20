<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Instruktur
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Instruktur</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/instruktur/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Nama Instruktur <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="nama" name="nama" type="text" class="form-control" placeholder="Nama Instruktur"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">No. HP </label>
                            <div class="col-sm-6">
                                <input id="hp" name="hp" type="text" class="form-control" placeholder="Nomor HP">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Alamat </label>
                            <div class="col-sm-6">
                                <input id="alamat" name="alamat" type="text" class="form-control" placeholder="Alamat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Email <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = array('1'=>'Aktif','2'=>'Blokir','3'=>'Tutup Akun');
                                    echo form_dropdown('status',$status,'',array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/instruktur/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>