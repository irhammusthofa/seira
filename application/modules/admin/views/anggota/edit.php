<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Anggota
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Anggota</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/anggota/simpan/'.base64_encode($data['anggota']->a_kode),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Kode Anggota <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="kode" name="kode" type="text" class="form-control" placeholder="Kode Anggota" value="<?= $data['anggota']->a_kode ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Nama Anggota <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="nama" name="nama" type="text" class="form-control" placeholder="Nama Anggota" value="<?= $data['anggota']->a_nama ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">No. HP </label>
                            <div class="col-sm-6">
                                <input id="hp" name="hp" type="text" class="form-control" placeholder="Nomor HP" value="<?= $data['anggota']->a_hp ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Alamat </label>
                            <div class="col-sm-6">
                                <input id="alamat" name="alamat" type="text" class="form-control" placeholder="Alamat" value="<?= $data['anggota']->a_alamat ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Email</label>
                            <div class="col-sm-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="<?= $data['anggota']->u_email ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Password</label>
                            <div class="col-sm-6">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Kosongkan jika tdk ada perubahan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jenis <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $jenis = array('no'=>'Non Member','yes'=>'Member');
                                    echo form_dropdown('jenis',$jenis,$data['anggota']->a_member,array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = array('1'=>'Aktif','2'=>'Blokir','3'=>'Tutup Akun');
                                    echo form_dropdown('status',$status,$data['anggota']->u_status,array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/anggota/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>