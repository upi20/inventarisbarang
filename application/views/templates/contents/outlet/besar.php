<div class="card card-info card-outline" id="filter">
    <div class="card-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-end align-items-star w-100 flex-md-row flex-column">
                <h3 class="card-title align-self-center">Filter <?= $title ?>: </h3>
                <div class="ml-md-2">
                    <select class="form-control" id="filter-sales" name="filter-sales" style=" min-width:200px"></select>
                </div>
                <div class="ml-md-2">
                    <select class="form-control" id="filter-kecamatan" name="filter-kecamatan" style=" min-width:200px"></select>
                </div>
                <div class="ml-md-2">
                    <input type="text" class="form-control" id="filter-penjualan" name="filter-penjualan" placeholder="Jumlah Penjualan">
                </div>
                <div class="ml-md-2">
                    <button type="button" class="btn btn-info btn" id="btn-filter"><i class="fas fa-search"></i></i> Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /List Warung -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">List Warung</h3>
        </div>
    </div>
    <div class="card-body">
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="max-width: 40px;">NO</th>
                    <th style="width: 15%;">Kecamatan</th>
                    <th style="width: 10%;">Sales</th>
                    <th>Warung</th>
                    <th style="width: 15%;">Produk</th>
                    <th style="width: 10%;">Qty Terjual (Karton)</th>
                    <th style="width: 10%;">Qty Terjual (Renceng)</th>
                    <th style="width: 12%;">Total Kredit</th>
                </tr>
            </thead>
        </table>
    </div>

</div>