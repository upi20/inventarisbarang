$(function () {
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>warung/penjualan/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": "warung" },
                { "data": "sales" },
                { "data": "produk" },
                {
                    "data": "harga", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "jumlah", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "total_harga", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "dibayar", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "sisa", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "setoran", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                { "data": "penerima" },
                {
                    "data": "hutang", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                { "data": "status_str" },
                {
                    "data": "updated_at", render(data, type, full, meta) {
                        return data == null || data == '' ? full.created_at : full.updated_at;
                        
                    }, className: "nowrap"
                },
                {
                    "data": "id", render(data, type, full, meta) {
                        return `<div class="pull-right">
									<button class="btn btn-primary btn-xs" onclick="Ubah(${data})">
										<i class="fa fa-edit"></i> Ubah
									</button>
									<button class="btn btn-danger btn-xs" onclick="Hapus(${data})">
										<i class="fa fa-trash"></i> Hapus
									</button>
								</div>`
                    }, className: "nowrap"
                }
            ],
        });
    }
    dynamic();

    $("#btn-tambah").click(() => {
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#warung', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_warung', null, '#myModal', 'Pilih Warung');
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#sales', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#produk', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_produk', null, '#myModal', 'Pilih Produk');
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#penerima', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_penerima', null, '#myModal', 'Pilih Penerima');

        ForForm();
        $("#myModalLabel").text("Tambah List penjualan");
        $('#id').val("");
        $('#harga').val("");
        $('#jumlah').val("");
        $('#total_harga').val("");
        $('#dibayar').val("");
        $('#sisa').val("");
        $('#setoran').val("");
        $('#hutang').val("");
        $('#status').val("1");
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>warung/penjualan/' + ($("#id").val() == "" ? 'insert' : 'update'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            dynamic();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#myModal').modal('toggle')
        })
    });

    // hapus
    $('#OkCheck').click(() => {
        let id = $("#idCheck").val()
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>warung/penjualan/delete',
            data: {
                id: id
            }
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil dihapus'
            })
            dynamic();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal dihapus'
            })
        }).always(() => {
            $('#ModalCheck').modal('toggle')
            $.LoadingOverlay("hide");
        })
    })
})

// Click Hapus
const Hapus = (id) => {
    $("#idCheck").val(id)
    $("#LabelCheck").text('Form Hapus')
    $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
    $('#ModalCheck').modal('toggle')
}

// Click Ubah
const Ubah = (id) => {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>warung/penjualan/getPenjualan',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#warung', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_warung', null, '#myModal', 'Pilih Warung', data.id_warung);
            ajax_select(false, '#sales', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_sales', null, '#myModal', 'Pilih Sales', data.id_sales);
            ajax_select(false, '#produk', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_produk', null, '#myModal', 'Pilih Produk', data.id_produk);
            ajax_select(false, '#penerima', '<?= base_url(); ?>/warung/penjualan/ajax_select_list_penerima', null, '#myModal', 'Pilih Penerima', data.id_penerima);
            $('docum')
            ForForm();
            $("#myModalLabel").text("Ubah List Penjualan");
            $('#id').val(data.id);
            $('#harga').val(data.harga);
            $('#jumlah').val(data.jumlah);
            $('#total_harga').val(data.total_harga);
            $('#dibayar').val(data.dibayar);
            $('#sisa').val(data.sisa);
            $('#setoran').val(data.setoran);
            $('#hutang').val(data.hutang);
            $('#status').val(data.status);
            $('#myModal').modal('toggle')
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Data tidak valid.'
            })
        }
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data.'
        })
    }).always(() => {
        $.LoadingOverlay("hide");
    })
}


function ForForm() {

    $('#produk').change(function () {
        var selected = '';
        $.ajax({
            method: 'post',
            url: '<?= base_url(); ?>warung/penjualan/produkById',
            data: {
                id: this.value,
                select : ''
            },
        }).done((data) => {
            data.forEach(e => {
                $('#harga').val(e.harga_jual);
                $('#jumlah').val("0");
                $('#total_harga').val("0");
                $('#dibayar').val("0");
                $('#sisa').val("0");
                $('#setoran').val("0");
                $('#hutang').val("0");
            });

            $("#jumlah, #harga, #dibayar, #setoran").change(function() {
                var harga  = $("#harga").val();
                var jumlah = $("#jumlah").val();
                var total1 = parseInt(harga) * parseInt(jumlah);
                $("#total_harga").val(total1);

                var total_harga  = $("#total_harga").val();
                var dibayar = $("#dibayar").val();
                var total2 = parseInt(total_harga) - parseInt(dibayar);
                $("#sisa").val(total2);

                var sisa  = $("#sisa").val();
                var setoran = $("#setoran").val();
                var total3 = parseInt(sisa) - parseInt(setoran);
                $("#hutang").val(total3);
            })

            $("#jumlah, #harga, #dibayar, #setoran").keyup(function() {
                var harga  = $("#harga").val();
                var jumlah = $("#jumlah").val();
                var total1 = parseInt(harga) * parseInt(jumlah);
                $("#total_harga").val(total1);

                var total_harga  = $("#total_harga").val();
                var dibayar = $("#dibayar").val();
                var total2 = parseInt(total_harga) - parseInt(dibayar);
                $("#sisa").val(total2);

                var sisa  = $("#sisa").val();
                var setoran = $("#setoran").val();
                var total3 = parseInt(sisa) - parseInt(setoran);
                $("#hutang").val(total3);
            })

        }).fail(($xhr) => {
            setToast('danger', 'danger', 'Failed', 'Gagal mendapatkan data');
        })
    })

}
