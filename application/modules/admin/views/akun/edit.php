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
                <?= form_open('admin/akun/simpan/'.base64_encode($data['akun']->u_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Username <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="username" name="username" type="text" class="form-control" placeholder="Username" value="<?= $data['akun']->u_name ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Password</label>
                            <div class="col-sm-6">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Kosongkan jika tidak ada perubahan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Email <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="<?= $data['akun']->u_email ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Role </label>
                            <div class="col-sm-6">
                                <input id="role" name="role" type="text" class="form-control" value="<?= $data['akun']->u_role ?>"  disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = ['0'=>'Tidak aktif','1'=>'Aktif']; echo form_dropdown('status',$status,$data['akun']->u_status,array('class'=>'form-control','required'=>'true','id'=>'status')) ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/akun/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>