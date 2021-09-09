$(function () {
    // initial daterange picker
    $('#filter_date').daterangepicker();
    function dynamic(date_start = null, date_end = null, admin = null) {
        let filter = null;
        if (date_start != null && date_end != null && admin != null) {
            filter = {
                admin: admin,
                date_start: date_start,
                date_end: date_end,
            }
        } else {
            if (global_level == 'Admin') {
                filter = {
                    admin: $('#penanggung_jawab').val()
                }
            }
        }
        const column = [];
        if (global_level == 'Administrator') column.push({ "data": "penanggung_jawab" });
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>stok/masuk/ListMasuk/ajax_data/",
                "data": filter,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": null },
                { "data": "id", className: "nowrap" },
                ...column,
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
                    "data": "updated_at", render(data, type, full, meta) {
                        return data == null || data == '' ? full.created_at : full.updated_at;

                    }, className: "nowrap"
                },
                {
                    "data": "id", render(data, type, full, meta) {
                        return `<div class="pull-right">
                                    <button class="btn btn-success btn-xs" onclick="Detail('${data}')">
                                        <i class="fa fa-list"></i> Detail
                                    </button>
									<a href="<?= base_url('stok/masuk/tambah') ?>?edit=${full.id}" class="btn btn-primary btn-xs">
										<i class="fa fa-edit"></i> Ubah
									</a>
									<button class="btn btn-danger btn-xs" onclick="Hapus('${data}')">
										<i class="fa fa-trash"></i> Hapus
									</button>
								</div>`
                    }, className: "nowrap"
                }
            ],
            columnDefs: [{
                orderable: false,
                targets: [0]
            }],
            order: [
                [1, 'asc']
            ]
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

    $("#btn-filter").click(() => {
        const date = $('#filter_date').data('daterangepicker');
        const admin = $('#penanggung_jawab').val();
        dynamic(date.startDate.format('YYYY-MM-DD'), date.endDate.format('YYYY-MM-DD'), admin);
    })

    $("#btn-tambah").click(() => {
        ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#sales', '<?= base_url(); ?>/stok/masuk/ListMasuk/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
        $("#myModalLabel").text("Tambah Pengadaan");
        $('#id').val("");
        $('#jumlah').val("");
        $('#status').val("1");
    });

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/masuk/ListMasuk/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
            url: '<?= base_url() ?>stok/masuk/ListMasuk/delete',
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
        url: '<?= base_url() ?>stok/masuk/ListMasuk/getProduk',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#sales', '<?= base_url(); ?>/stok/masuk/ListMasuk/ajax_select_list_sales', null, '#myModal', 'Pilih Sales', data.id_sales);
            $("#myModalLabel").text("Ubah Pengadaan");
            $('#id').val(data.id);
            $('#jumlah').val(data.jumlah);
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

const Detail = (datas) => {
    $('#main-content').LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>stok/masuk/ListMasuk/getDetailStokMasuk',
        data: {
            id: datas
        }
    }).done((data) => {
        $("#myModal").modal('toggle');

        // render header
        $('#detail_total_harga').html(format_rupiah(data.total_harga, false));
        $('#detail_dibayar').html(format_rupiah(data.dibayar, false));
        $('#detail_sisa').html(format_rupiah(data.sisa, false));
        $('#detail_jumlah_produk').html(data.details.length);
        $('#detail_tanggal_disimpan').html(data.created_at);
        $('#detail_tanggal_diubah').html(data.updated_at);
        $('#detail_pj').html(`Penanggung Jawab: ${data.pj}`);
        $('#detail_kode').html(`Kode: ${data.kode}`);

        // render body
        const table_body = $("#detail-stok-masuk-body");
        table_body.html('');
        let table_body_html = '';
        let number = 1;
        data.details.forEach(e => {
            table_body_html += `
                <tr>
                    <td>${number++}</td>
                    <td>${e.produk_nama}</td>
                    <td>${e.satuan_nama}</td>
                    <td class="text-right">${format_rupiah(e.harga)}</td>
                    <td>${e.jumlah}</td>
                    <td class="text-right">${format_rupiah(e.total_harga)}</th>
                </tr>
                `;
        });
        table_body.html(table_body_html);
        renderTable('#detail-stok-masuk');

    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data.'
        })
    }).always(() => {
        $('#main-content').LoadingOverlay("hide");
    })
}

function renderTable(element_table) {
    $(element_table).dataTable().fnDestroy();
    const tableUser = $(element_table).DataTable({
        columnDefs: [{
            orderable: false,
            targets: [0]
        }],
        order: [
            [1, 'asc']
        ]
    });
    tableUser.on('draw.dt', function () {
        var PageInfo = $(element_table).DataTable().page.info();
        tableUser.column(0, {
            page: 'current'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    });
}