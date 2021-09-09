<form action="" method="post" id="form-registrasi">
    <div class="form-group">
        <label for="sekolah">Sekolah</label>
        <select class="form-control" id="sekolah" name="sekolah" style="min-width: 100px;" required>
        </select>
    </div>
    <div class="form-group">
        <label for="kelas">Kelas</label>
        <select class="form-control" id="kelas" name="kelas" style="min-width: 100px;" required>
        </select>
    </div>
    <div class="form-group">
        <label for="nip">NIP</label>
        <input type="text" name="nip" class="form-control" id="nip" placeholder="Nomor Induk Pegawai" required>
    </div>
    <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Lengkap Guru" required>
    </div>
    <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat Guru" required></textarea>
    </div>
    <div class="form-group">
        <label for="tanggal_lahir">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir" required />
    </div>
    <div class="form-group">
        <label for="jenis_kelamin">Jenis Kelamin</label>
        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
            <option value="Laki-Laki">Laki-Laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
    </div>

    <div class="form-group">
        <label for="no_telpon">Nomor Telepon</label>
        <input type="text" name="no_telpon" class="form-control" id="no_telpon" placeholder="Nomor Telepon" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6">
    </div>
    <div class="form-group">
        <label for="password1">Ulangi Password</label>
        <input type="password" name="password1" class="form-control" id="password1" placeholder="Ulangi Password" required minlength="6">
    </div>

    <div class="d-flex justify-content-between">
        <div class="icheck-primary">
            <input type="checkbox" id="password-visibility">
            <label for="password-visibility">
                Lihat password
            </label>
        </div>
    </div>
</form>

<div class="social-auth-links text-center mt-2 mb-3">
    <button type="submit" form="form-registrasi" class="btn btn-block btn-info">
        <i class="fas fa-sign-in-alt"></i> Registrasi
    </button>
</div>