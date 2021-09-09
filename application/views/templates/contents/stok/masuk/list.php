<div class="card card-info card-outline" id="filter">
    <div class="card-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-end  align-items-star w-100 flex-md-row flex-column">
                <h3 class="card-title align-self-center">Filter Stok Masuk: </h3>
                <div class="form-group  mb-lg-0 ml-lg-2">
                    <select <?= $level != 'Admin' ? '' : 'disabled style="display:none"'; ?> class="form-control" id="penanggung_jawab" name="penanggung_jawab" required style="width: 100%;">
                        <?php if ($level != 'Admin') : ?>
                            <option value="">Pilih Penanggung jawab</option>
                        <?php endif; ?>
                        <?php foreach ($list_admin as $admin) : ?>
                            <option value="<?= $admin['id']; ?>"><?= $admin['text']; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group  mb-lg-0 ml-lg-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="filter_date">
                    </div>
                    <!-- /.input group -->
                </div>
                <div class=" ml-lg-2">
                    <button type="button" class="btn btn-info btn" id="btn-filter" style="min-width: 72px;"><i class="fas fa-search"></i></i> Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">List Stok Masuk</h3>
            <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button> -->
            <a href="<?= base_url(); ?>stok/masuk/tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="max-width: 40px;">NO</th>
                    <th>ID</th>
                    <?php if ($level != 'Admin') : ?>
                        <th>Penanggung Jawab</th>
                    <?php endif; ?>
                    <th>Total Harga</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h4 class="modal-title" id="myModalLabel">Detail Stok Masuk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-md-flex justify-content-between">
                    <h5 id="detail_kode"></h5>
                    <span id="detail_pj"></span>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-7">
                        <table>
                            <tr>
                                <td style="width: 150px;">Total Harga</td>
                                <td style="width: 3px;">:</td>
                                <td id="detail_total_harga"></td>
                            </tr>
                            <tr>
                                <td>Dibayar</td>
                                <td>:</td>
                                <td id="detail_dibayar"></td>
                            </tr>
                            <tr>
                                <td>Sisa</td>
                                <td>:</td>
                                <td id="detail_sisa"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-5">
                        <table>
                            <tr>
                                <td style="width: 150px;">Jumlah Produk</td>
                                <td style="width: 3px;">:</td>
                                <td id="detail_jumlah_produk"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Disimpan</td>
                                <td>:</td>
                                <td id="detail_tanggal_disimpan"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Diubah</td>
                                <td>:</td>
                                <td id="detail_tanggal_diubah"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <label for="detail-stok-masuk">List Produk:</label>
                <table class="table" id="detail-stok-masuk">
                    <thead>
                        <tr>
                            <th scope="col" style="max-width:10px">No</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Satuan Harga</th>
                            <th scope="col">Harga</th>
                            <th scope="col" style="max-width:10px">Qty</th>
                            <th scope="col">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody id="detail-stok-masuk-body">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    const global_level = '<?= $level ?>';
</script>