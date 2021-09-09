<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-home"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Sales</span>
                <span class="info-box-number">
                    <?=$total_sales?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Warung</span>
                <span class="info-box-number"><?=$total_warung?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Kecamatan</span>
                <span class="info-box-number"><?=$total_kecamatan?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Wilayah</span>
                <span class="info-box-number"><?=$total_wilayah?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Stok Diluar</span>
                <span class="info-box-number"><?=$stok_diluar?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Kredit</span>
                <span class="info-box-number"><?=$this->libs->rupiah($total_kredit)?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-home"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Pemasukan</span>
                <span class="info-box-number"><?=$this->libs->rupiah($total_pemasukan)?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Pengadaan</span>
                <span class="info-box-number"><?=$this->libs->rupiah($total_pengadaan)?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>