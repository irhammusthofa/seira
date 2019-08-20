<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Kategori Latihan
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Kategori Latihan</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/instruktur/kategori/simpan/'.base64_encode($data['instruktur']->i_kode).'/'.base64_encode($data['instruktur-detail']->il_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Kategori <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('kategori',$data['kategori'],$data['instruktur-detail']->k_id,array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php $status = array('0'=>'Tidak Aktif','1'=>'Aktif');
                                    echo form_dropdown('status',$status,$data['instruktur-detail']->status,array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/instruktur/kategori/'.base64_encode($data['instruktur']->i_kode),'Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>