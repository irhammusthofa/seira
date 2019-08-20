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
                <?= form_open('anggota/jadwal/simpan/'.base64_encode($data['anggota-jadwal']->aj_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">ID Paket</label>
                            <div class="col-sm-6">
                                <input type="text" name="paket" id="paket" value="<?= $data['anggota-jadwal']->p_id ?>" class="form-control" disabled>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pertemuan ke</label>
                            <div class="col-sm-2">
                                <input type="text" name="pertemuan" class="form-control" value="<?= @$data['anggota-jadwal']->pertemuan_ke ?>" disabled>
                            </div>
                            <div class="col-sm-3">
                                <?php 
                                    echo form_dropdown('tanggal',$data['tanggal'],$data['anggota-jadwal']->ji_tgl,array('class'=>'form-control','onchange'=>'loadjadwal()','required'=>'true','id'=>'tanggal')) ?>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary btn-xs" type="button" onclick="loadjadwal()"><i class="fa fa-search"></i></button>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jadwal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php 
                                    echo form_dropdown('jadwal',$data['instruktur-jadwal'],$data['anggota-jadwal']->id_jadwal_instruktur,array('id'=>'jadwal', 'class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group" id="div-loading" hidden>
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                    <img src="<?= base_url('assets/ajax-loader.gif') ?>"> Loading
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