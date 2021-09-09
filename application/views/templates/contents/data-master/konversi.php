<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between w-100">
                <h3 class="card-title">Konversi</h3>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form id="form" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="karton">Karton</label>
                        <input type="number" class="form-control" id="karton" name="karton" placeholder="Karton">
                    </div>
                    <div class="form-group">
                        <label for="renceng">Renceng</label>
                        <input type="number" class="form-control" id="renceng" name="renceng" placeholder="Renceng">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="datetime" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" disabled>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-success" id="simpan" name="simpan"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" id="reset" name="reset" class="btn btn-warning"><i class="fa fa-redo"></i> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
</div>