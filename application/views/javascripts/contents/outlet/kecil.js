$(function () {
    $('#filter-sales').select2();
    $('#filter-kecamatan').select2();
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
                "url": "<?= base_url()?>outlet/kecil/ajax_data/",
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
                {
                    "data": "kecamatan", render(data, type, full, meta) {
                        return full.kode_kecamatan + '-' + data;
                    }, className: "text-center"
                },
                { "data": "sales" },
                { "data": "warung" },
                { "data": "produk" },
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
                {
                    "data": "kredit", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data, null, 'Rp. ');
                    }, className: "text-right"
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
    ajax_select_kode({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#filter-kecamatan', '<?= base_url(); ?>/outlet/kecil/ajax_select_list_kecamatan', null, '#myModal', 'Pilih Kecamatan');
    ajax_select({ id: '#btn-tambah', pretext: 'Tambah', text: '<i class="fa fa-plus"></i> Tambah' }, '#filter-sales', '<?= base_url(); ?>/outlet/kecil/ajax_select_list_sales', null, '#myModal', 'Pilih Sales');
    dynamic();
    $('#sales').change(function () {

    })

    $("#btn-filter").click(() => {
        const sales = $('#filter-sales').val();
        const kecamatan = $('#filter-kecamatan').val();
        const penjualan = $('#filter-penjualan').val();
        dynamic(sales, kecamatan, penjualan);
    });
})
