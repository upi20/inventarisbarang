<div class="card card-info card-outline" id="filter">
    <div class="card-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-end  align-items-star w-100 flex-md-row flex-column">
                <h3 class="card-title align-self-center">Filter <?= $title ?>: </h3>
                <div class="form-group mr-md-1 mx-xs-0 mb-lg-0 ml-lg-2">
                    <select class="form-control" name="filter-status" id="filter-status" style="min-width:200px">
                        <option value="">Pilih Status</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Hutang">Hutang</option>
                    </select>
                </div>
                <div class=" ml-lg-2">
                    <button type="button" class="btn btn-info btn" id="btn-filter"><i class="fas fa-search"></i></i> Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /List Warung -->
<div class="card card-primary card-outline mb-0">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">Detail <?= $kod[0]['id_stok_keluar'] ?></h3>
        </div>
    </div>
    <div class="card-body">
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="max-width: 35px;">NO</th>
                    <th>Sales</th>
                    <th>Warung</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah (Karton)</th>
                    <th>Jumlah (Renceng)</th>
                    <th>Total Harga</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Penerima</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>

        <form id="form" enctype="multipart/form-data">
            <input type="hidden" name="id_stok_keluar" id="id_stok_keluar" value="<?= $kod[0]['id_stok_keluar'] ?>">
            <div class="col">
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Total Tagihan</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="number" id="total_harga" name="total_harga" class="form-control text-right" value="<?= $kod[0]['sisa'] ?>" readonly>
                    </div>
                </div>
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Setoran</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="number" id="setoran" name="setoran" value="0" class="form-control text-right">
                    </div>
                </div>
                <div class="row justify-content-end m-1">
                    <div class="col-12 col-md-6 col-lg-8">
                        <label class="float-right">Sisa</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="number" id="sisa" name="sisa" class="form-control text-right" value="0" readonly>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col">
                    <button class="btn btn-success float-right m-1" type="submit">Setor</button>
                    <a href="<?= base_url() ?>sales/penjualan" class="btn btn-danger float-right m-1">Batal</a>
                </div>
            </div>
        </form>
    </div>

</div>