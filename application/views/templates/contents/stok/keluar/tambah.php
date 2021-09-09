<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">Tambah Stok keluar</h3>
            <span id="title-status" class="text-success">Tambah Produk</span>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" id="card-body-form-tambah">
        <form id="main-form">
            <div class="row">
                <!-- detail stok keluar -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id_stok_keluar">Kode</label>
                        <input type="text" id="id_stok_keluar" name="id_stok_keluar" class="form-control" placeholder="Kode" value="<?= $data['id']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d H:i:s'); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="penanggung_jawab">Penanggung Jawab</label>
                        <select <?= $level != 'Admin' ? '' : 'disabled'; ?> class="form-control" id="penanggung_jawab" name="penanggung_jawab" style="width: 100%;">
                            <?php if ($level != 'Admin') : ?>
                                <option value="">Pilih Penanggung jawab</option>
                            <?php endif; ?>
                            <?php foreach ($list_admin as $admin) :
                                // set penanggungjawab
                                $selected = $admin['id'] == $data['id_penanggung_jawab'] ? 'selected' : ''; ?>
                                <option <?= $selected ?> value="<?= $admin['id']; ?>"><?= $admin['text']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sales">Sales</label>
                        <select class="form-control" id="sales" name="sales" style="width: 100%;">
                            <option value="">Pilih Sales</option>
                            <?php foreach ($list_sales as $sales) :
                                // set penanggungjawab
                                $selected = $sales['id'] == $data['id_sales'] ? 'selected' : ''; ?>
                                <option <?= $selected ?> value="<?= $sales['id']; ?>"><?= $sales['text']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <!-- input tambah dan ubah -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="produk">Produk</label>
                        <select class="form-control" id="produk" name="produk" style="width: 100%;">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control" name="jumlah" id="jumlah" value="">
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" id="btn-sumbit-produk" class="btn btn-success">Tambah</button>
                        <button type="button" id="btn-reset" class="btn btn-danger" style="display: none;">Batal</button>
                    </div>
                </div>

                <!-- table list produk -->
                <div class="col-md-6">
                    <table id="dt_basic" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="max-width:25px">No</th>
                                <th>Produk</th>
                                <th style="max-width:65px">Jumlah</th>
                                <th style="max-width:30px">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col">
                    <button class="btn btn-success float-right m-1" type="submit" id="btn-simpan">Simpan</button>
                    <a href="<?= base_url() ?>stok/keluar/ListKeluar" class="btn btn-danger float-right m-1">Kembali</a>
                </div>
            </div>
        </form>
    </div>
    <!-- /.card-body -->
</div>
<script>
    const global_edit = <?= isset($_GET['edit']) ? 'true' : 'false' ?>;
</script>