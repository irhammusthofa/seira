<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Nilai
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Nilai</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php //form_open('instruktur/nilai/simpan/'.base64_encode($data['id_kategori']).'/'.base64_encode($data['id_paket']),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Aspek yang dinilai</th>
                                    <th colspan="8" style="vertical-align: middle;text-align: center;">Pertemuan</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Total Skor</th>  
                                </tr>
                                
                                <tr>
                                    <?php for($i=1;$i<9;$i++){ ?>
                                        <th style="vertical-align: middle;text-align: center;"><?= $i ?></th>
                                    <?php }?>    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $kriteria = $this->m_kriteria->by_kategori($data['id_kategori'])->result();
                                    $skor[1] = 0;
                                    $skor[2] = 0;
                                    $skor[3] = 0;
                                    $skor[4] = 0;
                                    $skor[5] = 0;
                                    $skor[6] = 0;
                                    $skor[7] = 0;
                                    $skor[8] = 0;

                                    foreach ($kriteria as $item) {
                                        $srow[1] = 0;
                                        $srow[2] = 0;
                                        $srow[3] = 0;
                                        $srow[4] = 0;
                                        $srow[5] = 0;
                                        $srow[6] = 0;
                                        $srow[7] = 0;
                                        $srow[8] = 0;
                                        echo '<tr>';
                                        for($i=0;$i<=9;$i++){
                                            if ($i==0){
                                                echo '<td>';
                                                echo $item->kr_kriteria;
                                                echo '</td>';
                                            }else if($i==9){
                                                echo '<td>';
                                                echo $srow[1] + $srow[2] + $srow[3] + $srow[4] + $srow[5] + $srow[6] + $srow[7] + $srow[8];
                                                echo '</td>';
                                            }else{

                                                $param['paket'] = $data['id_paket'];
                                                //$param['id_instruktur'] = $data['id_instruktur'];
                                                $param['pertemuan'] = $i;
                                                $param['id_kriteria'] = $item->kr_id;
                                                $nilai = $this->m_nilai->by_pertemuan_kriteria($param);
                                                
                                                if ($nilai['status'] == false){
                                                    echo '<td width="60px" style="text-align:center;vertical-align:middle">';
                                                    echo '';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td width="60px">';
                                                    echo '<input type="text" class="form-control" name="'.$item->kr_id.$i.'" value="'.$nilai['value'].'" style="width:60px;text-align:center;vertical-align:middle" disabled/>';
                                                    echo '</td>';
                                                    $skor[$i] += $nilai['value'];
                                                    $srow[$i] += $nilai['value'];
                                                }
                                                
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                    echo '<tr>';

                                        $srow[1] = 0;
                                        $srow[2] = 0;
                                        $srow[3] = 0;
                                        $srow[4] = 0;
                                        $srow[5] = 0;
                                        $srow[6] = 0;
                                        $srow[7] = 0;
                                        $srow[8] = 0;
                                    for($i=0;$i<=9;$i++){


                                        if ($i==0){
                                            echo '<th>';
                                            echo 'KEHADIRAN';
                                            echo '</th>';
                                        }else if($i==9){
                                            echo '<th>';
                                            echo $srow[1] + $srow[2] + $srow[3] + $srow[4] + $srow[5] + $srow[6] + $srow[7] + $srow[8];
                                            echo '</th>';
                                        }else{
                                            $param['paket'] = $data['id_paket'];
                                            //$param['id_instruktur'] = $data['id_instruktur'];
                                            $param['pertemuan'] = $i;
                                            $param['id_kriteria'] = '';
                                            $nilai = $this->m_nilai->by_pertemuan_kriteria($param);
                                            
                                            if ($nilai['status'] == false){
                                                echo '<th width="60px" style="text-align:center;vertical-align:middle">';
                                                echo '';
                                                echo '</th>';
                                            }else{
                                                $checked = ($nilai['aj_absen']==1) ? 'checked' : '';
                                                echo '<th width="60px" style="width:60px;text-align:center;vertical-align:middle">';
                                                echo '<input type="checkbox" name="abs'.$i.'" value="1" '.$checked.' disabled/>';
                                                echo '</th>';
                                                $srow[$i] += $nilai['aj_absen'];
                                            }
                                            
                                        }
                                    }

                                        $srow[1] = 0;
                                        $srow[2] = 0;
                                        $srow[3] = 0;
                                        $srow[4] = 0;
                                        $srow[5] = 0;
                                        $srow[6] = 0;
                                        $srow[7] = 0;
                                        $srow[8] = 0;
                                    echo '</tr>';
                                    echo '<tr>';
                                    for($i=0;$i<=9;$i++){
                                        if ($i==0){
                                            echo '<th>';
                                            echo 'JUMLAH';
                                            echo '</th>';
                                        }else if($i==9){
                                            echo '<th>';
                                            echo $srow[1] + $srow[2] + $srow[3] + $srow[4] + $srow[5] + $srow[6] + $srow[7] + $srow[8];
                                            echo '</th>';
                                        }else{
                                            $param['paket'] = $data['id_paket'];
                                            //$param['id_instruktur'] = $data['id_instruktur'];
                                            $param['pertemuan'] = $i;
                                            $param['id_kriteria'] = '';
                                            $nilai = $this->m_nilai->by_pertemuan_kriteria($param);
                                            
                                            if ($nilai['status'] == false){
                                                echo '<th width="60px" style="text-align:center;vertical-align:middle">';
                                                echo '';
                                                echo '</th>';
                                            }else{
                                                echo '<th width="60px" style="text-align:center;vertical-align:middle">';
                                                echo $skor[$i];
                                                echo '</th>';
                                                $srow[$i] += $skor[$i];
                                            }
                                            
                                        }
                                    }
                                    echo '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/nilai/','Kembali', array('class'=>'btn btn-default')) ?> &nbsp;
                    </div>
                </div>
                <!-- /.box-footer -->
                <?php //form_close() ?>
            </div>
        </div>
    </div>
</section>