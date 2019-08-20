<?php 

fs_add_assets_header('<link rel="stylesheet" href="'.fs_theme_path().'bower_components/select2/dist/css/select2.min.css">');
fs_add_assets_footer('<script src="'.fs_theme_path().'bower_components/select2/dist/js/select2.full.min.js"></script>');

$script = $this->load->view('paket/script_footer_form','',TRUE);
fs_add_assets_footer($script);