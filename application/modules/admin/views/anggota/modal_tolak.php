<div class="modal fade" id="modal-tolak-anggota">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tolak aktivasi</h4>
      </div>
      <?= form_open('admin/aktivasi/tolak/',array('method'=>'post','class'=>'form-horizontal','id'=>'frmTolak')) ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <span>Kode Registrasi</span>
          </div>
          <div class="col-md-8">
            <label id="tolak_kode"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <span>Nama</span>
          </div>
          <div class="col-md-8">
            <label id="tolak_nama"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Alasan Tolak</label>
            <input type="text" name="alasan" class="form-control" required>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger pull-right">Kirim</button>
      </div>
      <?= form_close() ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>