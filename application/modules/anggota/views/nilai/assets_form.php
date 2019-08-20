<?php

fs_add_assets_footer('<script src="'.fs_theme_path().'bower_components/chart.js/Chart.js"></script>');
fs_add_assets_footer('<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>');


$script_footer = $this->load->view('nilai/script_footer_form','',TRUE);
fs_add_assets_footer($script_footer);