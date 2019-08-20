<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Akun
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Akun</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/akun/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Username <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="username" name="username" type="text" class="form-control" placeholder="Username"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Password <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Email <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Role </label>
                            <div class="col-sm-6">
                                <input id="role" name="role" type="text" class="form-control" value="admin" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = ['0'=>'Tidak aktif','1'=>'Aktif']; echo form_dropdown('status',$status,'',array('class'=>'form-control','required'=>'true','id'=>'status')) ?>
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