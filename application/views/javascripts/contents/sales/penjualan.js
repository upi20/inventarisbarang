$(function () {
    function dynamic(sales, kecamatan, penjualan) {
        let data = null;
        if (sales || kecamatan || penjualan) {
            data = {
                filter: {
                    sales: sales,
                    kecamatan: kecamatan,
                    penjualan: penjualan,
                }
            }
        }
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>sales/penjualan/ajax_data/",
                "data": data,
                "type": 'POST'
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "columns": [
                { "data": null },
                { "data": "id" },
                { "data": "sales" },
                { "data": "produk" },
                {
                    "data": "jumlah", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data);
                    }, className: "text-right"
                },
                {
                    "data": "karton", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data);
                    }, className: "text-right"
                },
                {
                    "data": "renceng", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data);
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
									<a class="btn btn-success btn-xs" href="<?= base_url() ?>sales/penjualan/detailPenjualan/${data}" >
                                    <i class="fa fa-file"></i> Detail
									</a>
								</div>`
                    }, className: "nowrap"
                }
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 9]
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
        const sales = $('#filter-sales').val();
        const kecamatan = $('#filter-kecamatan').val();
        const penjualan = $('#filter-penjualan').val();
        dynamic(sales, kecamatan, penjualan);
    });
})

const Detail = (id) => {
    $.LoadingOverlay("show");
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>sales/penjualan/getPenjualan',
        data: {
            id: id
        }
    }).done((data) => {
        if (data.data) {
            data = data.data;
            window.location.href = '<?= base_url() ?>sales/penjualan/detailPenjualan/' + data.id_stok_keluar;
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