<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between w-100">
            <h3 class="card-title">List Warung</h3>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= base_url() ?>data-master/warung/export_excel" class="btn btn-success btn-sm"><i class="fa fa-print"></i> <span>Cetak Excel</span></a>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#import"><i class="fa fa-file"></i> <span>Import</span></button>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
                </div>
            </div>

        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="max-width: 40px;">NO</th>
                    <th style="width: 15%;">Kode</th>
                    <th>Sales</th>
                    <th>Kecamatan</th>
                    <th>Kategori</th>
                    <th>Warung</th>
                    <th>Pemilik</th>
                    <th>Alamat</th>
                    <th>No Hp</th>
                    <th>Patokan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sales">Sales</label>
                                <select class="form-control" id="sales" name="sales">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori">Kategori Warung</label>
                                <select class="form-control" id="kategori" name="kategori" required>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warung">Nama Warung</label>
                                <input type="text" class="form-control" id="warung" name="warung" placeholder="Nama Warung" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Pemilik</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pemilik" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="no_hp">No Hp</label>
                                <input type="number" id="no_hp" class="form-control" name="no_hp" placeholder="No Hp" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="patokan">Patokan</label>
                                <textarea name="patokan" id="patokan" rows="3" class="form-control" placeholder="Patokan" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kordinat">Kordinat</label>
                                <textarea name="kordinat" id="kordinat" rows="3" class="form-control" placeholder="Kordinat" required></textarea>
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


<!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" action="<?= base_url() ?>data-master/warung/import" id="form-import">
                <div class="modal-header">
                    <h3 class="modal-title custom-font">Import Data</h3>
                </div>
                <div class="modal-body">
                    <label>File(.xls) <a href="<?= base_url() ?>data-master/warung/downloadSample">Download sample file import</a></label>
                    <br>
                    <input type="file" name="file" id="file">
                </div>
                <div class="modal-footer">
                    <button id="clickImport" type="submit" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c"><i class="fa fa-arrow-right"></i> Submit</button>

                    <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tidak</button>
                </div>
            </form>
        </div>
    </div>
</div>