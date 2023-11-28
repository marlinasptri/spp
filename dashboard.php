<?php
if (!empty($_SESSION['user']['level'])) {
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Aplikasi Pembayaran SPP</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Petugas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelas");
                                $sum = mysqli_fetch_assoc($query);
                                echo $sum['total'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jumlah Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelas");
                                $sum = mysqli_fetch_assoc($query);
                                echo $sum['total'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Siswa
                            </div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php
                                $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa");
                                $sum = mysqli_fetch_assoc($query);
                                echo $sum['total'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pembayaran
                            </div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php
                                $query = mysqli_query($koneksi, "SELECT sum(jumlah_bayar) AS total FROM pembayaran");
                                $sum = mysqli_fetch_assoc($query);
                                echo 'Rp '  . number_format($sum['total'], 2, ",", ".");
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <h1 class="h3 mb-3" style="text-align: center;">History Siswa, <?php echo $_SESSION['user']['nama'] ?></h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Petugas</th>
                                <th>Nama Siswa</th>
                                <th>Tanggal Bayar</th>
                                <th>Bulan Bayar</th>
                                <th>Tahun Bayar</th>
                                <th>SPP</th>
                                <th>Jumlah Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $id = $_SESSION['user']['nisn'];
                            $query = mysqli_query($koneksi, "SELECT * FROM pembayaran INNER JOIN petugas ON pembayaran.id_petugas=petugas.id_petugas INNER JOIN siswa ON pembayaran.nisn=siswa.nisn INNER JOIN spp ON pembayaran.id_spp=spp.id_spp WHERE pembayaran.nisn='$id'");
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $data['nama_petugas'] ?></td>
                                    <td><?php echo $data['nama'] ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($data['tgl_bayar'])) ?></td>
                                    <td><?php echo $data['bulan_bayar'] ?></td>
                                    <td><?php echo $data['tahun_bayar'] ?></td>
                                    <td><?php echo $data['tahun'] ?> - Rp. <?php echo $data['nominal'] ?></td>
                                    <td> Rp. <?php echo $data['jumlah_bayar'] ?></td>
                                    <td>
                                        <?php
                                        if ($data['nominal'] > $data['jumlah_bayar']) {
                                            echo 'Kurang';
                                        } else {
                                            echo 'Lunas';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>