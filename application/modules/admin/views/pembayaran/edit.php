<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Pembayaran
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Pembayaran</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/pembayaran/simpan/'.base64_encode($data['pembayaran']->pm_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Id Pembayaran</label>
                            <div class="col-sm-6">
                                <input id="id" name="id" type="text" class="form-control" placeholder="Id Pembayaran" value="<?= $data['pembayaran']->pm_id ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Paket <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('paket',$data['paket'],$data['pembayaran']->id_paket,array('id'=>'paket','class'=>'form-control  select2','required'=>'true','onchange'=>'loadpaket()')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal Bayar </label>
                            <div class="col-sm-6">
                                <input id="tgl" name="tgl" type="datetime-local" class="form-control" placeholder="Tanggal daftar" value="<?= date('Y-m-d\TH:i:s', strtotime($data['pembayaran']->pm_tgl)) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Jumlah Bayar </label>
                            <div class="col-sm-6">
                                <input id="biaya" name="biaya" type="number" class="form-control" placeholder="Jumlah Bayar" value="<?= $data['pembayaran']->pm_biaya ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/pembayaran/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>