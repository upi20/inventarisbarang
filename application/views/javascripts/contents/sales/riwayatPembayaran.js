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
                "url": "<?= base_url()?>sales/riwayatPembayaran/ajax_data/",
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
                { "data": "warung" },
                { "data": "sales" },
                { "data": "penerima" },
                {
                    "data": "total_harga", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data, null, 'Rp. ');
                    }, className: "text-right"
                },
                {
                    "data": "dibayar", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data, null, 'Rp. ');
                    }, className: "text-right"
                },
                {
                    "data": "sisa", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data, null, 'Rp. ');
                    }, className: "text-right"
                },
                { "data": "created_at" }
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
        const sales = $('#filter-sales').val();
        const kecamatan = $('#filter-kecamatan').val();
        const penjualan = $('#filter-penjualan').val();
        dynamic(sales, kecamatan, penjualan);
    });
})
