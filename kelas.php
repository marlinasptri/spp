<?php
if (isset($_POST['simpan'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi_keahlian = $_POST['kompetensi_keahlian'];

    $query = mysqli_query($koneksi, "INSERT INTO kelas (nama_kelas,kompetensi_keahlian) VALUES ('$nama_kelas','$kompetensi_keahlian')");

    if ($query) {
        echo '<script>alert("Data Berhasil Ditambah");location.href="?page=kelas";</script>';
    }
}

if (isset($_POST['edit'])) {
    $id_kelas = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi_keahlian = $_POST['kompetensi_keahlian'];

    $query = mysqli_query($koneksi, "UPDATE kelas SET nama_kelas='$nama_kelas',kompetensi_keahlian='$kompetensi_keahlian' WHERE id_kelas='$id_kelas'");
    if ($query) {
        echo '<script>alert("Data Berhasil Diedit");location.href="?page=kelas"</script>';
    }
}
if (isset($_POST['hapus'])) {
    $id_kelas = $_POST['id_kelas'];

    $query = mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas='$id_kelas'");

    if ($query) {
        echo '<script>alert("Data Berhasil Dihapus");location.href="?page=kelas"</script>';
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
<h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahkelas">
            +Tambah Kelas
        </button>
    </div>
    <div class="card-body">
        <div class="table-rersponsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Kompetensi Keahlian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM kelas");
                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $data['nama_kelas'] ?></td>
                            <td><?php echo $data['kompetensi_keahlian'] ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editkelas<?php echo $data['id_kelas'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapuskelas<?php echo $data['id_kelas'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="editkelas<?php echo $data['id_kelas'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="col-sm-12">
                                            <div class="text-center">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Kelas</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <input type="hidden" name="id_kelas" value="<?php echo $data['id_kelas'] ?>">
                                                <label type="form-label">Nama Kelas</label>
                                                <input type="text" name="nama_kelas" class="form-control" value="<?php echo $data['nama_kelas'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kompetensi Keahliian</label>
                                                <input type="text" name="kompetensi_keahlian" class="form-control" value="<?php echo $data['kompetensi_keahlian'] ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-sm-12">
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="sumbit" class="btn btn-primary" name="edit">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="hapuskelas<?php echo $data['id_kelas'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Kelas</h5>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_kelas" value="<?php echo $data['id_kelas'] ?>">
                                            <div class="text-center">
                                                <span>Yakin hapus data?</span><br>
                                                <div class="text-danger">
                                                    Nama Kelas - <?php echo $data['nama_kelas'] ?><br>
                                                    Kompetensi Keahlian - <?php echo $data['kompetensi_keahlian'] ?>
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahkelas" tabindex="-1" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Kelas</h1>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kompetensi Keahliian</label>
                        <input type="text" name="kompetensi_keahlian" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>