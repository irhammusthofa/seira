<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Ruangan
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Ruangan</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/ruangan/simpan/'.base64_encode($data['ruangan']->r_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Nama Ruangan <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="ruangan" name="ruangan" type="text" class="form-control" placeholder="Nama Ruangan" value="<?= $data['ruangan']->r_ruangan ?>" 
                                    required>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/ruangan/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>