<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Jadwal
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Jadwal</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('anggota/jadwal/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Paket <b style="color:red">*</b></label>
                            <div class="col-sm-5">
                                <?php 
                                    echo form_dropdown('paket',$data['paket'],'',array('class'=>'form-control','onchange'=>'loadpertemuan()','required'=>'true','id'=>'paket')) ?>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary btn-xs" type="button" onclick="loadpertemuan()"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pertemuan ke <b style="color:red">*</b></label>
                            <div class="col-sm-2">
                                <?php 
                                    echo form_dropdown('pertemuan',$data['pertemuan'],'',array('class'=>'form-control','required'=>'true','id'=>'pertemuan')) ?>
                            </div>
                            <div class="col-sm-3">
                                <?php 
                                    echo form_dropdown('tanggal',$data['tanggal'],'',array('class'=>'form-control','onchange'=>'loadjadwal()','required'=>'true','id'=>'tanggal')) ?>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary btn-xs" type="button" onclick="loadjadwal()"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                       <!--  <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal <b style="color:red">*</b></label>
                            <div class="col-sm-4">
                                <input type="date" name="tgl" class="form-control" id="tgl" required >
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" onclick="loadjadwal()">Load Jadwal</button>
                            </div>
                        </div> -->
                        <div class="form-group" id="div-loading" hidden>
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                    <img src="<?= base_url('assets/ajax-loader.gif') ?>"> Loading
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jadwal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('jadwal',$data['instruktur-jadwal'],'',array('id'=>'jadwal', 'class'=>'form-control','onchange'=>'','required'=>'true')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('anggota/jadwal/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>