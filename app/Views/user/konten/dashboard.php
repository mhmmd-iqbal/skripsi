<?= $this->extend('admin/admin-template') ?>

<?= $this->section('css') ?>
<style>
    .title {
        font-size: 2.5vmin;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

<?= $this->endSection('css') ?>

<?= $this->section('konten') ?>
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">GRAFIK DATA SISTEM</p>
            </div>
            <div class="panel-body">
                <div class=" row">
                    <div class="col-lg-6 text-center">
                        <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 10px">
                            <h4 style="font-weight: bold; ">Jumlah Data Desa</h4>
                            <canvas id="chartKecamatan" width="auto" height="150"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 10px">
                            <h4 style="font-weight: bold; ">Jumlah Data User</h4>
                            <canvas id="chartUser" width="auto" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Grafik Penjualan Getah Karet Kabupaten Aceh Tenggara</p>
                <p class="title">Tahun <span id="yearShow"><?= date('Y') ?></span> </p>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2" style="">
                        <label for="">TAHUN TAMPIL</label>
                        <select name="year" id="year" name="year" class="form-control">
                            <?php for ($year = date('Y'); $year >= $tahunMulai; $year--) { ?>
                                <option value="<?= $year ?>"> <?= $year ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 10px">
                                <canvas id="chartPendapatan" width="auto" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 10px">
                                <canvas id="chartProduksi" width="auto" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">

    </div>
</div>
<?= $this->endSection('konten') ?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<?php
$dataKecamatan  = null;
$jmlDesa        = null;
$color          = null;

foreach ($kecamatan as $d) {
    $dataKecamatan .=  '"' . $d['kecamatan'] . '"' . ',';
    $jmlDesa .= $d['totalDesa'] . ',';
    $color .= '"' . 'rgba(' . rand(1, 255) . ',' . rand(1, 255) . ',' . rand(1, 255) . ' )' . '"' . ',';
}

$colorUser = null;
$level = null;
$totalUser = null;
foreach ($countUser as $d) {
    $level .=  '"' . $d['level'] . '"' . ',';
    $totalUser .= $d['totalUser'] . ',';
    $colorUser .= '"' . 'rgba(' . rand(1, 255) . ',' . rand(1, 255) . ',' . rand(1, 255) . ' )' . '"' . ',';
}

?>

<script>
    var kecamatan = document.getElementById('chartKecamatan').getContext('2d');

    var chartKecamatan = new Chart(kecamatan, {
        type: 'doughnut',
        data: {
            labels: [
                <?= $dataKecamatan ?>
            ],
            datasets: [{
                label: "Data Kecamatan",
                data: [<?= $jmlDesa ?>],
                // Set More Options
                lineTension: 0,
                pointBorderColor: 'orange',
                pointBackgroundColor: 'rgba(255,150,0,0.5)',
                backgroundColor: [<?= $color ?>],
                pointRadius: 3,
            }]
        },
        options: {
            cutoutPercentage: 50
        }
    });


    var user = document.getElementById('chartUser').getContext('2d');

    var chartUser = new Chart(user, {
        type: 'doughnut',
        data: {
            labels: [<?= $level ?>],
            datasets: [{
                label: "Data User",
                data: [<?= $totalUser ?>],
                // Set More Options
                lineTension: 0,
                pointBorderColor: 'orange',
                pointBackgroundColor: 'rgba(255,150,0,0.5)',
                backgroundColor: [<?= $colorUser ?>],
                pointRadius: 3,
            }]
        },
    });


    let yearFormat = new Date();
    let year = yearFormat.getFullYear();

    $('#year').on('change', function() {
        grafikPenjualan($(this).val())
        $('#yearShow').html($(this).val())
    })
    grafikPenjualan(year)

    function grafikPenjualan(year) {
        var pendapatan = document.getElementById('chartPendapatan').getContext('2d');
        var produksi = document.getElementById('chartProduksi').getContext('2d');
        $.ajax({
            type: "GET",
            url: "admin/grafik/penjualan/" + year,
            dataType: "JSON",
            success: function(res) {
                dataPendapatan = []
                dataProduksi = []
                labelName = []
                colorChange = []
                $.each(res, function(index, value) {
                    dataPendapatan.push(value.totalPendapatan);
                    dataProduksi.push(value.totalProduksi);
                    labelName.push(value.kecamatan);
                    r = Math.floor(Math.random() * 256);
                    g = Math.floor(Math.random() * 256);
                    b = Math.floor(Math.random() * 256);
                    colorChange.push("rgb(" + r + "," + g + "," + b + ")")
                });

                var chartPendapatan = new Chart(pendapatan, {
                    type: 'bar',
                    data: {
                        labels: labelName,
                        datasets: [{
                            label: "Data Pendapatan",
                            data: dataPendapatan,
                            lineTension: 0,
                            pointBorderColor: 'orange',
                            pointBackgroundColor: 'rgba(255,150,0,0.5)',
                            pointRadius: 3,
                            backgroundColor: colorChange,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

                var chartProduksi = new Chart(produksi, {
                    type: 'bar',
                    data: {
                        labels: labelName,
                        datasets: [{
                            label: "Data Produksi",
                            data: dataProduksi,
                            lineTension: 0,
                            pointBorderColor: 'orange',
                            pointBackgroundColor: 'rgba(255,150,0,0.5)',
                            pointRadius: 3,
                            backgroundColor: colorChange,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection('js') ?>