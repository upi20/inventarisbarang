$(function () {
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>penggajian/daftar/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": "user_nama" },
                {
                    "data": "jumlah", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "bonus", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "hutang", render(data, type, full, meta) {
                        return format_rupiah(data);
                    }, className: "text-right"
                },
                {
                    "data": "total_gaji", render(data, type, full, meta) {
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
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#sales', '<?= base_url(); ?>/penggajian/daftar/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
        hitung();
        $("#myModalLabel").text("Tambah Daftar Penggajian");
        $('#id').val("");
        $('#jumlah').val("0");
        $('#bonus').val("0");
        $('#hutang').val("0");
        $('#total_gaji').val("0");
        $('#status').val("1");
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>penggajian/daftar/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
            url: '<?= base_url() ?>penggajian/daftar/delete',
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
        url: '<?= base_url() ?>penggajian/daftar/getDaftar',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#sales', '<?= base_url(); ?>/penggajian/daftar/ajax_select_list_sales', null, '#myModal', 'Pilih Sales', data.id_sales);
            hitung();
            $("#myModalLabel").text("Ubah Daftar Penggajian");
            $('#id').val(data.id);
            $('#jumlah').val(data.jumlah);
            $('#bonus').val(data.bonus);
            $('#hutang').val(data.hutang);
            $('#total_gaji').val(data.total_gaji);
            $('#status').val(data.status);
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

function hitung() {
    $("#jumlah, #bonus, #hutang").change(function() {
        var jumlah  = $("#jumlah").val();
        var bonus = $("#bonus").val();
        var hutang = $("#hutang").val();
        var total_gaji = parseInt(jumlah) + parseInt(bonus) - parseInt(hutang);
        $("#total_gaji").val(total_gaji);
    })
    $("#jumlah, #bonus, #hutang").keyup(function() {
        var jumlah  = $("#jumlah").val();
        var bonus = $("#bonus").val();
        var hutang = $("#hutang").val();
        var total_gaji = parseInt(jumlah) + parseInt(bonus) - parseInt(hutang);
        $("#total_gaji").val(total_gaji);
    })
    
}