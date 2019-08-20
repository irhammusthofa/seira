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
                <?= form_open('admin/instruktur/simpan/'.base64_encode($data['instruktur']->i_kode),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Kode Instruktur <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="kode" name="kode" type="text" class="form-control" placeholder="Kode Instruktur" value="<?= $data['instruktur']->i_kode ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Nama Instruktur <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="nama" name="nama" type="text" class="form-control" placeholder="Nama Instruktur" value="<?= $data['instruktur']->i_nama ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">No. HP </label>
                            <div class="col-sm-6">
                                <input id="hp" name="hp" type="text" class="form-control" placeholder="Nomor HP" value="<?= $data['instruktur']->i_hp ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Alamat </label>
                            <div class="col-sm-6">
                                <input id="alamat" name="alamat" type="text" class="form-control" placeholder="Alamat" value="<?= $data['instruktur']->i_alamat ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Email</label>
                            <div class="col-sm-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="<?= $data['instruktur']->u_email ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Password</label>
                            <div class="col-sm-6">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Kosongkan jika tdk ada perubahan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = array('1'=>'Aktif','2'=>'Blokir','3'=>'Tutup Akun');
                                    echo form_dropdown('status',$status,$data['instruktur']->u_status,array('class'=>'form-control','required'=>'true')) ?>
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