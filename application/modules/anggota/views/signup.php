<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Signup</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= fs_theme_path() ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= fs_theme_path() ?>bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= fs_theme_path() ?>bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= fs_theme_path() ?>dist/css/AdminLTE.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url('anggota/signup') ?>"><b>Daftar</b> SEIRA</a>
        </div>
        <?php fs_show_alert() ?>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Silahkan lengkapi form Registrasi Anggota dibawah ini :.</p>
            <?= form_open('anggota/signup/dosignup',array('method'=>'post')) ?>
            <div class="form-group">
                <label class="control-label">Nama Lengkap <b style="color:red">*</b></label>
                <input id="nama" name="nama" type="text" class="form-control" placeholder="Nama Lengkap"
                        required>
            </div>
            
            <div class="form-group">
                <label class="control-label">No. HP </label>
                <input id="hp" name="hp" type="text" class="form-control" placeholder="Nomor HP">
            </div>
            <div class="form-group">
                <label class="control-label">Email <b style="color:red">*</b></label>
                <input id="email" name="email" type="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label class="control-label">Alamat </label>
                <input id="alamat" name="alamat" type="text" class="form-control" placeholder="Alamat">
            </div>
            <div class="form-group">
                <label class="control-label">Jenis Member <b style="color:red">*</b></label>
                <select class="form-control" name="jenis" required>
                    <option>Pilih Jenis Member</option>
                    <option value="yes">Member</option>
                    <option value="no">Non Member</option>
                </select>
            </div>
            <div class="row">
                <!-- /.col --><br>
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Daftar</button>
                </div><br><br>
                <p style="text-align:center">atau</p>
                <div class="col-xs-12 pull-right">
                    <?= anchor('anggota','Login',array('class'=>'btn btn-success btn-block btn-flat')) ?>
                </div>
                <!-- /.col -->
            </div>
            <?= form_close() ?>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="<?= fs_theme_path() ?>bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?= fs_theme_path() ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>

</html>