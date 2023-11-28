<?php
if (isset($_POST['simpan'])) {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn='$nisn'");
    $cek_nisn = mysqli_num_rows($cek);
    $cek1 = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis='$nis'");
    $cek_nis = mysqli_num_rows($cek1);

    if ($cek_nisn > 0) {
        echo '<script>alert("NISN Sudah Di Gunakan");location.href="?page=siswa";</script>';
    } elseif ($cek_nis > 0) {
        echo '<script>alert("NIS Sudah Di Gunakan");location.href="?page=siswa";</script>';
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO siswa (nisn,nis,nama,id_kelas,alamat,no_telp,password) VALUES('$nisn','$nis','$nama','$id_kelas','$alamat','$no_telp','$password')");

        if ($query) {
            echo '<script>alert("Data Berhasil Ditambah");location.href="?page=siswa";</script>';
        }
    }
}

if (isset($_POST['edit'])) {
    $oldnisn = $_POST['oldnisn'];
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $password = md5($_POST['password']);

    if (empty($_POST['password'])) {
        $query = mysqli_query($koneksi, "UPDATE siswa SET nisn='$nisn',nis='$nis',nama='$nama',id_kelas='$id_kelas',alamat='$alamat',no_telp='$no_telp' WHERE nisn='$oldnisn' ");
        if ($query) {
            echo '<script>alert("Data Berhasil Diedit");location.href="?page=siswa"</script>';
        }
    } else {
        $query = mysqli_query($koneksi, "UPDATE siswa SET nisn='$nisn',nis='$nis',nama='$nama',id_kelas='$id_kelas',alamat='$alamat',no_telp='$no_telp',password='$password' WHERE nisn='$oldnisn' ");
        if ($query) {
            echo '<script>alert("Data Berhasil Diedit");location.href="?page=siswa"</script>';
        }
    }
}

if (isset($_POST['hapus'])) {
    $nisn = $_POST['nisn'];

    $query = mysqli_query($koneksi, "DELETE FROM siswa WHERE nisn='$nisn'");

    if ($query) {
        echo '<script>alert("Data Berhasil Dihapus");location.href="?page=siswa"</script>';
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
<h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <?php
        if (!empty($_SESSION['user']['level'] == 'admin')) {
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahsiswa">
                + Tambah Siswa
            </button>
        <?php
        }
        ?>
    </div>
    <div class="card-body">
        <div class="table-rersponsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas");
                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $data['nisn'] ?></td>
                            <td><?php echo $data['nis'] ?></td>
                            <td><?php echo $data['nama'] ?></td>
                            <td><?php echo $data['nama_kelas'] ?></td>
                            <td><?php echo $data['kompetensi_keahlian'] ?></td>
                            <td><?php echo $data['alamat'] ?></td>
                            <td><?php echo $data['no_telp'] ?></td>
                            <td>
                                <?php
                                if (!empty($_SESSION['user']['level'] == 'admin')) {
                                ?>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editsiswa<?php echo $data['nisn'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapussiswa<?php echo $data['nisn'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php
                                }
                                ?>
                                <a href="?page=history&nisn=<?php echo $data['nisn'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-archive"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal fade" id="editsiswa<?php echo $data['nisn'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="col-sm-12">
                                            <div class="text-center">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Siswa</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <input type="hidden" name="oldnisn" value="<?php echo $data['nisn'] ?>">
                                                <label type="form-label">Nisn</label>
                                                <input type="text" name="nisn" class="form-control" value="<?php echo $data['nisn'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nis</label>
                                                <input type="text" name="nis" class="form-control" value="<?php echo $data['nis'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Siswa</label>
                                                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelas dan Jurusan</label>
                                                <div class="text-danger">
                                                    <select name="id_kelas" class="form-control" required>
                                                        <option value="">-Pilih-</option>
                                                        <?php
                                                        $query1 = mysqli_query($koneksi, "SELECT * FROM kelas");
                                                        while ($kelas = mysqli_fetch_array($query1)) {
                                                        ?>
                                                            <option value="<?php echo $kelas['id_kelas'] ?>" <?php echo ($data['id_kelas'] == $kelas['id_kelas'] ? 'selected' : '') ?>>
                                                                <?php echo $kelas['nama_kelas'] ?> - <?php echo $kelas['kompetensi_keahlian'] ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <input type="text" name="alamat" class="form-control" value="<?php echo $data['alamat'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No telp</label>
                                                <input type="text" name="no_telp" class="form-control" value="<?php echo $data['no_telp'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-sm-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="sumbit" class="btn btn-primary" name="edit">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="hapussiswa<?php echo $data['nisn'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Siswa</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="nisn" value="<?php echo $data['nisn'] ?>">
                                            <div class="text-center">
                                                <span>Yakin hapus data?</span><br>
                                                <div class="text-danger">
                                                    NISN - <?php echo $data['nisn'] ?><br>
                                                    Nama Siswa - <?php echo $data['nama'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                                            <button type="sumbit" class="btn btn-primary" name="hapus">Delete</button>
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
<script>
    $(dokument).ready(function() {
        $('#siswa').DataTable();
    });
</script>
<div class="modal fade" id="tambahsiswa" tabindex="-1" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-sm-12">
                    <div class="text-cemter">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel">Siswa</h1>
                    </div>
                </div>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama siswa</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas dan Jurusan</label>
                        <div class="text-danger">
                            <select name="id_kelas" id="kelas" class="form-control" required>
                                <option value="">-Pilih-</option>
                                <?php
                                $query2 = mysqli_query($koneksi, "SELECT * FROM kelas");
                                while ($kelas = mysqli_fetch_array($query2)) {
                                ?>
                                    <option value="<?php echo $kelas['id_kelas']  ?>">
                                        <?php echo $kelas['nama_kelas'] ?> - <?php echo $kelas['kompetensi_keahlian'] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No telp</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>