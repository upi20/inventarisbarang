<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">Tambah Stok Masuk</h3>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah Produk</button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form id="main-form">
            <div class="row">
                <!-- detail stok masuk -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" id="kode" name="kode" class="form-control" placeholder="Kode" value="<?= $data['id']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d H:i:s'); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="penanggung_jawab">Penanggung Jawab</label>
                        <select <?= $level != 'Admin' ? '' : 'disabled'; ?> class="form-control" id="penanggung_jawab" name="penanggung_jawab" required style="width: 100%;">
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
                </div>

                <!-- table list produk -->
                <div class="col-md-9">
                    <table id="dt_basic" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="max-width: 40px;">NO</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty (Karton)</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- footer -->
            <div class="col">
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Total Harga</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="hidden" id="total_harga" name="total_harga" class="form-control text-right" value="" disabled>
                        <input type="text" id="total_harga_text" name="total_harga_text" class="form-control text-right" value="" disabled>
                    </div>
                </div>
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Dibayar</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="hidden" id="dibayar" name="dibayar" value="<?= $data["dibayar"] ?>" class="form-control">
                        <input type="text" id="dibayar_text" name="dibayar_text" value="<?= $data["dibayar"] ?>" class="form-control text-right">
                    </div>
                </div>
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Sisa</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="hidden" id="sisa" name="sisa" class="form-control text-right" disabled>
                        <input type="text" id="sisa_text" name="sisa_text" class="form-control text-right" disabled>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col">
                    <button class="btn btn-success float-right m-1" type="submit">Simpan</button>
                    <a href="<?= base_url() ?>stok/masuk/ListMasuk" class="btn btn-danger float-right m-1">Kembali</a>
                </div>
            </div>
        </form>
    </div>
    <!-- /.card-body -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-produk" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_stok_masuk" id="id_stok_masuk" value="<?= $data['id']; ?>">

                    <div class="form-group row">
                        <label for="produk" class="col-sm-3 col-form-label text-md-right">Produk</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="produk" name="produk" required style="width: 100%;">

                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="satuan_harga" class="col-sm-3 col-form-label text-md-right">Satuan Harga</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="satuan_harga" name="satuan_harga" required style="width: 100%;">

                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="harga_text" class="col-sm-3 col-form-label text-md-right">Harga</label>
                        <div class="col-sm-9">
                            <input type="hidden" class="form-control" id="harga" name="harga" required readonly />
                            <input type="text" class="form-control-plaintext" id="harga_text" name="harga_text" required readonly style="color: white;" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jumlah" class="col-sm-3 col-form-label text-md-right">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ttl_harga_text" class="col-sm-3 col-form-label text-md-right">Total Harga</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="ttl_harga" class="form-control" name="ttl_harga" required readonly />
                            <input type="text" id="ttl_harga_text" class="form-control-plaintext" name="ttl_harga_text" required readonly style="color: white;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <select class="form-control" id="satuan" name="satuan">
                                    <option selected value="karton">Karton</option>
                                    <option value="renceng">Renceng</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Tambah
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Batal
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    const global_edit = <?= isset($_GET['edit']) ? 'true' : 'false' ?>;
</script>