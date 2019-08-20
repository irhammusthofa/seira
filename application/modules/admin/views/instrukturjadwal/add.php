<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Jadwal Instruktur
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Jadwal Instruktur</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/jadwal/instruktur/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Instruktur <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('instruktur',$data['instruktur-latihan'],'',array('class'=>'form-control','required'=>'true','id'=>'datepicker')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="date1" type="date" name="tgl" class="form-control" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jam Mulai<b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input type="time" name="mulai" class="form-control" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jam selesai<b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input type="time" name="selesai" class="form-control" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Ruangan <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('ruangan',$data['ruangan'],'',array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Kuota Peserta<b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input type="number" name="kuota" class="form-control" placeholder="Kuota Peserta" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tipe Member <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('tipe',$data['tipe'],'',array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/jadwal/instruktur/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>