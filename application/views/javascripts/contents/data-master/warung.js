$(function () {

    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>data-master/warung/ajax_data/",
                "data": null,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": null },
                { "data": "kode" },
                { "data": "sales" },
                { "data": "kecamatan" },
                { "data": "kategori" },
                { "data": "warung" },
                { "data": "nama" },
                {
                    "data": "alamat", render(data, type, full, meta) {
                        const num = 20;
                        const text = data == null ? '' : String(data).substring(0, num) + (String(data).length > num ? '...' : '');
                        return text;
                    }
                },
                { "data": "no_hp" },
                {
                    "data": "patokan", render(data, type, full, meta) {
                        const num = 20;
                        const text = data == null ? '' : String(data).substring(0, num) + (String(data).length > num ? '...' : '');
                        return text;
                    }
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
                },
            ],
            columnDefs: [{
                orderable: false,
                targets: [0]
            }],
            order: [
                [1, 'asc']
            ],
            createdRow: function (row, data, index, cells) {

                handleToltipFromDatatable({ element: cells[7], title: data.alamat });
                handleToltipFromDatatable({ element: cells[9], title: data.patokan });
            }
        });
        tableUser.on('draw.dt', function () {
            var PageInfo = $(table_html).DataTable().page.info();
            tableUser.column(0, {
                page: 'current'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });
    }
    dynamic();

    $("#btn-tambah").click(() => {
        ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#sales', '<?= base_url(); ?>/data-master/warung/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
        ajax_select_kode({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#kecamatan', '<?= base_url(); ?>/data-master/warung/ajax_select_list_kecamatan', null, '#myModal', 'Pilih Kecamatan');
        ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#kategori', '<?= base_url(); ?>/data-master/warung/ajax_select_list_kategori', null, '#myModal', 'Pilih Kategori');
        $("#myModalLabel").text("Tambah List Warung");
        $('#id').val("");
        $('#warung').val("");
        $('#nama').val("");
        $('#alamat').val("");
        $('#no_hp').val("");
        $('#patokan').val("");
        $('#kordinat').val("");
        $('#jenis').val("");
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>data-master/warung/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
            url: '<?= base_url() ?>data-master/warung/delete',
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

    $("#form-import").submit(function () {
        $("#clickImport").attr("disabled", "");
    });

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
        url: '<?= base_url() ?>data-master/warung/getListWarung',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#sales', '<?= base_url(); ?>/data-master/warung/ajax_select_list_sales', null, '#myModal', 'Pilih Sales', data.id_sales);
            ajax_select_kode(false, '#kecamatan', '<?= base_url(); ?>/data-master/warung/ajax_select_list_kecamatan', null, '#myModal', 'Pilih Kecamatan', data.id_kecamatan);
            ajax_select(false, '#kategori', '<?= base_url(); ?>/data-master/warung/ajax_select_list_kategori', null, '#myModal', 'Pilih Kategori', data.id_kategori);
            $("#myModalLabel").text("Ubah List Warung");
            $('#id').val(data.id);
            $('#warung').val(data.nama_warung);
            $('#nama').val(data.nama_pemilik);
            $('#alamat').val(data.alamat);
            $('#no_hp').val(data.no_hp);
            $('#patokan').val(data.patokan);
            $('#kordinat').val(data.kordinat);
            $('#jenis').val(data.jenis);
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

function handleToltipFromDatatable({ element = '', title = '' }) {
    const el = $(element);
    el.tooltip({ title: title == null ? '' : title });
    el.mouseenter(function () {
        $(this).tooltip('show');
    });
    el.mouseout(function () {
        $(this).tooltip('hide');
    });
}