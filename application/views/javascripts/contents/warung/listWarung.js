$(function () {
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>warung/listWarung/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": "kecamatan" },
                { "data": "kategori" },
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "no_hp" },
                { "data": "kordinat" },
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
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#kecamatan', '<?= base_url(); ?>/warung/listWarung/ajax_select_list_kecamatan', null, '#myModal', 'Pilih Kota');
        ajax_select({id:'#btn-tambah', pretext:'Tambah', text:'<i class="fa fa-plus"></i> Tambah'}, '#kategori', '<?= base_url(); ?>/warung/listWarung/ajax_select_list_kategori', null, '#myModal', 'Pilih Kota');
        $("#myModalLabel").text("Tambah List Warung");
        $('#id').val("");
        $('#nama').val("");
        $('#alamat').val("");
        $('#no_hp').val("");
        $('#kordinat').val("");
        $('#jenis').val("");
        $('#status').val("1");
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>warung/listWarung/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
            url: '<?= base_url() ?>warung/listWarung/delete',
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
        url: '<?= base_url() ?>warung/listWarung/getListWarung',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#kecamatan', '<?= base_url(); ?>/warung/listWarung/ajax_select_list_kecamatan', null, '#myModal', 'Pilih Kota', data.id_kecamatan);
            ajax_select(false, '#kategori', '<?= base_url(); ?>/warung/listWarung/ajax_select_list_kategori', null, '#myModal', 'Pilih Kota', data.id_kategori);
            $("#myModalLabel").text("Ubah List Warung");
            $('#id').val(data.id);
            $('#nama').val(data.nama_pemilik);
            $('#alamat').val(data.alamat);
            $('#no_hp').val(data.no_hp);
            $('#kordinat').val(data.kordinat);
            $('#jenis').val(data.jenis);
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