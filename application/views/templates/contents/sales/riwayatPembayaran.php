<!-- Filter -->
<!-- <div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-2">
                <select class="form-control" id="filter-sales" name="filter-sales"></select>
            </div>
            <div class="col-2">
                <select class="form-control" id="filter-kecamatan" name="filter-kecamatan"></select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control" id="filter-penjualan" name="filter-penjualan" placeholder="Jumlah Penjualan">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-info btn" id="btn-filter"><i class="fas fa-search"></i></i> Cari</button>
            </div>
        </div>
    </div>
</div> -->
<!-- /.card-body -->

<!-- /List Warung -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">Riwayat Pembayaran</h3>
        </div>
    </div>
    <div class="card-body">
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="max-width: 40px;">NO</th>
                    <th>Warung</th>
                    <th>Sales</th>
                    <th>Penerima</th>
                    <th>Total Harga</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
        </table>
    </div>

</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table id="dt_basic1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
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
                            </tr>
                        </thead>
                    </table>

                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="setoran">Setoran</label>
                                <input type="number" id="setoran" class="form-control" name="setoran" placeholder="Setoran" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sisa">Sisa</label>
                                <input type="number" id="sisa" class="form-control" name="sisa" placeholder="Sisa" required />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->