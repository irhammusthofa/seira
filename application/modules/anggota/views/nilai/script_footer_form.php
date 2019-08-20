<script type="text/javascript">
    $(document).ready(function() {
            //$("#grafik").hide();
            //$("#loading").show();

            $.ajax({
                url: "<?= site_url('anggota/nilai/grafik/') ?>",
                dataType:"json",
                type: "get",
                success: function(response) {
                    //$("#grafik").show();
                    //$("#loading").hide();
                    if (response.status === false) {
                        alert(response.message);
                        //$("#grafik").hide();
                    } else {
                        //$("#grafik").show();
                        var chartData = {
                            labels: response.pertemuan,
                            datasets: [{
                                label: 'Nilai',
                                backgroundColor: 'rgba(255, 99, 132, 0.4)' ,
                                fillColor: 'rgba(210, 214, 222, 1)',
                                strokeColor: 'rgba(210, 214, 222, 1)',
                                pointColor: 'rgba(210, 214, 222, 1)',
                                pointStrokeColor: '#c1c7d1',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data: response.nilai
                            }]
                        };
                        // var areaChartData = {
                        //     labels: response.caleg,
                        //     datasets: [{
                        //         label: 'Caleg',
                        //         fillColor: 'rgba(210, 214, 222, 1)',
                        //         strokeColor: 'rgba(210, 214, 222, 1)',
                        //         pointColor: 'rgba(210, 214, 222, 1)',
                        //         pointStrokeColor: '#c1c7d1',
                        //         pointHighlightFill: '#fff',
                        //         pointHighlightStroke: 'rgba(220,220,220,1)',
                        //         data: response.suara
                        //     }]
                        // }

                        drawGrafik(chartData);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loading").hide();
                    alert("Error : " + textStatus);
                }
            });
        });
    function drawGrafik(chartData) {
        $('#barChart').remove(); // this is my <canvas> element
        $('#chart').append('<canvas id="barChart" style="height:300px"><canvas>');

        var barOptions = options = {
            maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        barPercentage: 0.5,
                        barThickness: 6,
                        maxBarThickness: 8,
                        minBarLength: 2,
                    }]
                }
            };



        var ctx = document.getElementById("barChart").getContext("2d");
        var myBar = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: barOptions
        });
    }
</script>