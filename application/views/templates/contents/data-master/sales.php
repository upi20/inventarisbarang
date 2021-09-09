<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-md-flex justify-content-between w-100">
            <h3 class="card-title">Data Sales</h3>
            <div>
                <a href="<?= base_url() ?>data-master/sales/export_excel" class="btn btn-info  btn-sm"><i class="fa fa-print"></i> <span>Cetak Excel</span></a>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#import"><i class="fa fa-download"></i> <span>Import</span></button>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $q) : ?>
                    <tr data-id="<?= $q['user_id'] ?>">
                        <td><?= $q['lev_nama'] ?></td>
                        <td><?= $q['user_email'] ?></td>
                        <td><?= $q['user_nama'] ?></td>
                        <td><?= $q['user_phone'] ?></td>
                        <td><?= $q['user_status'] == '1' ? 'Aktif' : ($q['user_status'] == '2' ? 'Pending' : 'Tidak Aktif') ?></td>
                        <td><?= $q['updated_at'] == null ? $q['created_at'] : $q['updated_at'] ?></td>
                        <td>
                            <div>
                                <button class="btn btn-primary btn-sm" onclick="Ubah(<?= $q['user_id'] ?>)">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="Hapus(<?= $q['user_id'] ?>)">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="level" id="level">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal">Nama</label>
                                <input type="text" id="nama" class="form-control" name="nama" placeholder="Nama" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal">Telepon</label>
                                <input type="number" id="phone" class="form-control" name="phone" placeholder="Telepon" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal">Username</label>
                                <input type="text" id="username" class="form-control" name="username" placeholder="Username" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Password</label>
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Ulangi Password</label>
                                <input type="password" id="upassword" class="form-control" name="upassword" placeholder="Ulangi Password" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">
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
            <form method="post" enctype="multipart/form-data" action="<?= base_url() ?>data-master/sales/import" id="form-import">
                <div class="modal-header">
                    <h3 class="modal-title custom-font">Import Data</h3>
                </div>
                <div class="modal-body">
                    <label>File(.xls) <a href="<?= base_url() ?>data-master/sales/export_excel">Download sample file import</a></label>
                    <br>
                    <input type="file" name="file" id="file">
                </div>
                <div class="modal-footer">
                    <button id="clickImport" type="submit" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c"><i class="fa fa-arrow-right"></i> Submit</button>
                    <button class="btn btn-danger btn-ef btn-ef-4 btn-ef-4c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Tidak</button>
                </div>
            </form>
        </div>
    </div>
</div>