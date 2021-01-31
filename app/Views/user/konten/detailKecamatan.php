<?= $this->extend('admin/admin-template') ?>

<?= $this->section('css') ?>
<style>
    .title {
        font-size: 2.5vmin;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }

    table {
        margin: 10px 0;
    }

    thead tr th {
        text-align: center;
    }

    .filter {
        margin: 0 0 10px 0;
    }

    .filter ul li {
        display: inline;
    }

    .aksi {
        text-align: center;
    }

    .aksi button {
        margin-right: 5px;
    }

    .aksi a {
        margin-right: 5px;
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
                <p class="title">Data Kecamatan <?= $kecamatan['kecamatan'] ?> </p>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="" style="text-transform: uppercase;">GRAFIK PRODUKSI KECAMATAN <?= $kecamatan['kecamatan'] ?></label>
                            </div>
                            <div class="col-lg-12">
                                <canvas id="chartProduksi" width="auto" height="100"></canvas>
                            </div>
                            <div class="col-lg-12">
                                <canvas id="chartPendapatan" width="auto" height="100"></canvas>
                            </div>
                            <div class="col-lg-12">
                                <canvas id="chartHargaRata" width="auto" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                    <tr>
                                        <th>Jumah Desa</th>
                                        <td><?= $totalDesa ?> Desa</td>
                                    </tr>
                                    <tr>
                                        <th>Mulai Produksi</th>
                                        <td>Tahun <?= $tahun[0] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Rata-rata Produksi</th>
                                        <td>
                                            <?php
                                            $sumProduksi = 0;
                                            $i = 0;
                                            foreach ($totalProduksi as $produksi) {
                                                $sumProduksi += $produksi;
                                                $i++;
                                            }
                                            echo number_format($sumProduksi / $i, 2, ',', '.') . " Ton";
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Rata-rata Pendapatan</th>
                                        <td>
                                            <?php
                                            $sumPendapatan = 0;
                                            $i = 0;
                                            foreach ($totalPendapatan as $pendapatan) {
                                                $sumPendapatan += $pendapatan;
                                                $i++;
                                            }
                                            echo "IDR. " . number_format($sumPendapatan / $i, 2, ',', '.');
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('konten') ?>

<?= $this->section('js') ?>
<?php
$dataProduksi = null;
$dataPendapatan = null;
$dataHargaRata = null;
$dataTahun = null;
foreach ($totalProduksi as $produksi) {
    $dataProduksi .= $produksi . ",";
}
foreach ($totalPendapatan as $pendapatan) {
    $dataPendapatan .= $pendapatan . ",";
}
foreach ($tahun as $d) {
    $dataTahun .= $d . ",";
}
foreach ($hargaRata as $hargaRata) {
    $dataHargaRata .= $hargaRata . ",";
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script>
    console.log('<?= $dataHargaRata ?>')
    var produksi = document.getElementById('chartProduksi').getContext('2d');
    var pendapatan = document.getElementById('chartPendapatan').getContext('2d');
    var hargaRata = document.getElementById('chartHargaRata').getContext('2d');
    var dataProduksi = {
        label: "Data Produksi",
        data: [<?= $dataProduksi ?>],
        lineTension: 0,
        // Set More Options
        borderColor: 'red',
        backgroundColor: 'transparent',
        pointBorderColor: 'red',
        pointBackgroundColor: 'rgba(255,150,0,0.5)',
        pointRadius: 3,
    };

    var dataPendapatan = {
        label: "Data Pendapatan",
        data: [<?= $dataPendapatan ?>],
        // Set More Options
        lineTension: 0,
        borderColor: 'orange',
        backgroundColor: 'transparent',
        pointBorderColor: 'orange',
        pointBackgroundColor: 'rgba(255,150,0,0.5)',
        pointRadius: 3,
    };

    var dataHargaRata = {
        label: "Data HargaRata",
        data: [<?= $dataHargaRata ?>],
        // Set More Options
        lineTension: 0,
        borderColor: 'lightgreen',
        backgroundColor: 'transparent',
        pointBorderColor: 'orange',
        pointBackgroundColor: 'rgba(255,150,0,0.5)',
        pointRadius: 3,
    };

    var chartProduksiData = {
        labels: [<?= $dataTahun ?>],
        datasets: [dataProduksi]
    };


    var chartProduksi = new Chart(produksi, {
        type: 'line',
        data: chartProduksiData,
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

    var chartPendapatanData = {
        labels: [<?= $dataTahun ?>],
        datasets: [dataPendapatan]
    };


    var chartPendapatan = new Chart(pendapatan, {
        type: 'line',
        data: chartPendapatanData,
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
    var chartHargaRataData = {
        labels: [<?= $dataTahun ?>],
        datasets: [dataHargaRata]
    };


    var chartHargaRata = new Chart(hargaRata, {
        type: 'line',
        data: chartHargaRataData,
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
</script>
<?= $this->endSection('js') ?>