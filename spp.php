<?php
if (isset($_POST['simpan'])) {
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    $query = mysqli_query($koneksi, "INSERT INTO spp (tahun,nominal) VALUES('$tahun','$nominal')");

    if ($query) {
        echo '<script>alert("Data Berhasil Ditambah");location.href="?page=spp"</script>';
    }
}
if (isset($_POST['edit'])) {
    $id_spp = $_POST['id_spp'];
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    $query = mysqli_query($koneksi, "UPDATE spp SET tahun='$tahun', nominal='$nominal' WHERE id_spp='$id_spp'");
    if ($query) {
        echo '<script>alert("Data Berhasil Diedit");location.href="?page=spp"</script>';
    }
}
if (isset($_POST['hapus'])) {
    $id_spp = $_POST['id_spp'];

    $query = mysqli_query($koneksi, "DELETE FROM spp WHERE id_spp='$id_spp'");

    if ($query) {
        echo '<script>alert("Data Berhasil Dihapus");location.href="?page=spp"</script>';
    }
}

if (empty($_SESSION['user']['level'] == 'admin')) {
?>
    <script>
        alert('Akses Tidak Diperbolehkan');
        window.history.back();
    </script>
<?php
}
?>
<h1 class="h3 mb-2 text-gray-800">Data SPP</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahspp">
            + Tambah SPP
        </button>
    </div>
    <div class="card-body">
        <div class="table-rersponsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Nominal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM spp");
                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $data['tahun'] ?></td>
                            <td> Rp. <?php echo number_format($data['nominal'], 2, ',', '.') ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editspp<?php echo $data['id_spp'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusspp<?php echo $data['id_spp'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="editspp<?php echo $data['id_spp'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="col-sm-12">
                                            <div class="text-center">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Data SPP</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <input type="hidden" name="id_spp" value="<?php echo $data['id_spp'] ?>">
                                                <label type="form-label">Tahun</label>
                                                <input type="text" name="tahun" class="form-control" value="<?php echo $data['tahun'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nominal</label>
                                                <input type="text" name="nominal" class="form-control" value="<?php echo $data['nominal'] ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="sumbit" class="btn btn-primary" name="edit">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="hapusspp<?php echo $data['id_spp'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data SPP</h5>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_spp" value="<?php echo $data['id_spp'] ?>">
                                            <div class="text-center">
                                                <span>Yakin ingin hapus data?</span><br>
                                                <div class="text-danger">
                                                    Tahun - <?php echo $data['tahun'] ?><br>
                                                    SPP - <?php echo $data['nominal'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-sm-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="sumbit" class="btn btn-primary" name="hapus">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    }

                    ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(documen).ready(function() {
        $('#spp').DataTable();
    });
</script>
<div class="modal fade" id="tambahspp" tabindex="-1" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data SPP</h1>
                    </div>
                </div>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="text" name="tahun" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input type="text" name="nominal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                        </div>
            </form>
        </div>
    </div>
</div>
</div>