$(function () {
    $('#sales').select2();
    // initial daterange picker
    $('#filter_date').daterangepicker();
    function dynamic(data = {
        start: null,
        end: null,
        admin: null,
        status: null,
        sales: null,
    }) {
        let filter = null;
        if (data.start != null && data.end != null && data.admin != null && data.status != null && data.sales != null) {
            filter = {
                admin: data.admin,
                date_start: data.start,
                date_end: data.end,
                status: data.status,
                sales: data.sales,
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
                "url": "<?= base_url()?>stok/keluar/ListKeluar/ajax_data/",
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
                    "data": "sales"
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
                                    <button class="btn btn-success btn-xs" onclick="Detail('${data}')">
                                        <i class="fa fa-list"></i> Detail
                                    </button>
									<a href="<?= base_url('stok/keluar/tambah') ?>?edit=${full.id}" class="btn btn-primary btn-xs">
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
        const status = $('#status').val();
        const sales = $('#sales').val();
        dynamic({
            start: date.startDate.format('YYYY-MM-DD'),
            end: date.endDate.format('YYYY-MM-DD'),
            admin: admin,
            status: status,
            sales: sales,
        });
    })

    $("#btn-tambah").click(() => {
        ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#sales', '<?= base_url(); ?>/stok/keluar/ListKeluar/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
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
            url: '<?= base_url() ?>stok/keluar/ListKeluar/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
            url: '<?= base_url() ?>stok/keluar/ListKeluar/delete',
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
        url: '<?= base_url() ?>stok/keluar/ListKeluar/getProduk',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            ajax_select(false, '#sales', '<?= base_url(); ?>/stok/keluar/ListKeluar/ajax_select_list_sales', null, '#myModal', 'Pilih Sales', data.id_sales);
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
        url: '<?= base_url() ?>stok/keluar/ListKeluar/getDetailStokKeluar',
        data: {
            id: datas
        }
    }).done((data) => {
        $("#myModal").modal('toggle');

        // render header
        $('#detail_jumlah_produk').html(data.details.length);
        $('#detail_tanggal_disimpan').html(data.created_at);
        $('#detail_tanggal_diubah').html(data.updated_at);
        $('#detail_pj').html(data.pj);
        $('#detail_kode').html(data.kode);
        $('#detail_sales').html(data.sales);

        // render body
        const table_body = $("#detail-stok-keluar-body");
        table_body.html('');
        let table_body_html = '';
        let number = 1;
        data.details.forEach(e => {
            table_body_html += `
                <tr>
                    <td>${number++}</td>
                    <td>${e.produk_nama}</td>
                    <td>${e.jumlah}</td>
                </tr>
                `;
        });
        table_body.html(table_body_html);
        renderTable('#detail-stok-keluar');

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