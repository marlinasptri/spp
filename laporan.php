<?php
if (isset($_POST['lunas'])) {
    $id_pembayaran = $_POST['id_pembayaran'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $bulan_bayar = $_POST['bulan_bayar'];
    $tahun_bayar = $_POST['tahun_bayar'];
    $kekurangan = $_POST['kekurangan'];
    $jumlah_bl = $_POST['jumlah_bl'];
    $jumlah_bb = $_POST['jumlah_bb'];

    $total = $jumlah_bl + $kekurangan;
    $total1 = $jumlah_bl + $jumlah_bb;
    $sisa = $jumlah_bb - $kekurangan;
    $sisa1 = $kekurangan - $jumlah_bb;

    if ($jumlah_bb > $kekurangan) {
        $query = mysqli_query($koneksi, "UPDATE pembayaran SET tgl_bayar='$tgl_bayar',bulan_bayar='$bulan_bayar',tahun_bayar='$tahun_bayar',jumlah_bayar='$total' WHERE id_pembayaran='$id_pembayaran'");
        echo '<script>alert("SPP terbayar || Saldo anda dikembalikan sebesar: Rp ' . number_format($sisa, 2, ',', '.') . '");location.href="index.php?page=laporan";</script>';
    } elseif ($jumlah_bb < $kekurangan) {
        $query = mysqli_query($koneksi, "UPDATE pembayaran SET tgl_bayar='$tgl_bayar',bulan_bayar='$bulan_bayar',tahun_bayar='$tahun_bayar',jumlah_bayar='$total1' WHERE id_pembayaran='$id_pembayaran'");
        echo '<script>alert("SPP terbayar || Kekurangan anda sebesar: Rp ' . number_format($sisa1, 2, ',', '.') . '");location.href="index.php?page=laporan";</script>';
    } else {
        $query = mysqli_query($koneksi, "UPDATE pembayaran SET tgl_bayar='$tgl_bayar',bulan_bayar='$bulan_bayar',tahun_bayar='$tahun_bayar',jumlah_bayar='$total1' WHERE id_pembayaran='$id_pembayaran'");
        echo '<script>alert("SPP terbayar || Saldo anda dikembalikan sebesar: Rp ' . number_format($sisa1, 2, ',', '.') . '");location.href="index.php?page=laporan";</script>';
    }
}

if (empty($_SESSION['user']['level'])) {
?>
    <script>
        alert('Akses Tidak Diperbolehkan');
        window.history.back();
    </script>
<?php
}
?>
<h1 class="h3 mb-2 text-gray-800">Laporan</h1>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-rersponsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM pembayaran INNER JOIN petugas ON pembayaran.id_petugas=petugas.id_petugas INNER JOIN siswa ON pembayaran.nisn=siswa.nisn INNER JOIN spp ON pembayaran.id_spp=spp.id_spp");
                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $data['nama_petugas'] ?></td>
                            <td><?php echo $data['nama'] ?></td>
                            <td><?php echo date('d-m-Y', strtotime($data['tgl_bayar'])) ?></td>
                            <td><?php echo $data['bulan_bayar'] ?></td>
                            <td><?php echo $data['tahun_bayar'] ?></td>
                            <td><?php echo $data['tahun'] ?> - Rp <?php echo number_format($data['nominal'], 2, ',', '.') ?></td>
                            <td> Rp. <?php echo number_format($data['jumlah_bayar'], 2, ',', '.') ?></td>
                            <td>
                                <?php
                                if ($data['nominal'] > $data['jumlah_bayar']) {
                                    echo 'Kurang';
                                } else {
                                    echo 'Lunas';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($data['nominal'] == $data['jumlah_bayar']) {
                                ?>
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fa fa-check"></i>
                                    </button>
                                <?php
                                } else {
                                ?>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editpembayaran<?php echo $data['id_pembayaran'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <div class="modal fade" id="editpembayaran<?php echo $data['id_pembayaran'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Pembayaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <input type="hidden" name="id_pembayaran" value="<?php echo $data['id_pembayaran'] ?>">
                                                <label type="form-label">Nama Siswa</label>
                                                <input type="text" name="nisn" class="form-control" value="<?php echo $data['nama'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Bayar</label>
                                                <input type="date" class="form-control" value="<?php echo $data['tgl_bayar'] ?>" disabled>
                                                <input type="hidden" name="tgl_bayar" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Bulan Bayar</label>
                                                <input type="text" class="form-control" value="<?php echo $data['bulan_bayar'] ?>" disabled>
                                                <input type="hidden" name="bulan_bayar" class="form-control" value="<?php echo date('F') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tahun Bayar</label>
                                                <input type="text" class="form-control" value="<?php echo $data['tahun_bayar'] ?>" disabled>
                                                <input type="hidden" name="tahun_bayar" class="form-control" value="<?php echo date('Y') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">SPP</label>
                                                <input type="text" name="id_spp" class="form-control" value="<?php echo $data['tahun'] ?> - <?php echo $data['nominal'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kekurangan</label>
                                                <input type="text" name="kekurangan" class="form-control" value="<?php echo $data['nominal'] -  $data['jumlah_bayar'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jumlah Bayar</label>
                                                <input type="hidden" name="jumlah_bl" class="form-control" value="<?php echo $data['jumlah_bayar'] ?>">
                                                <input type="text" name="jumlah_bb" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="sumbit" class="btn btn-primary" name="lunas">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if (!empty($_SESSION['user']['level'] == 'admin')) {
            ?>
                <div class="cotainer" style="text-align: center;">
                    <a href="cetak_pembayaran.php" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>