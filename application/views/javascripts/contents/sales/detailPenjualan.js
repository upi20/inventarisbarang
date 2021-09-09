$(function () {
    function dynamic(status) {
        let data = null;
        if (status) {
            data = {
                filter: {
                    status: status,
                }
            }
        }
        var getKode = window.location.pathname.split('/');
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy()
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>sales/penjualan/getDetail/" + getKode[6],
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
                { "data": "sales" },
                { "data": "warung" },
                { "data": "produk" },
                {
                    "data": "harga", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data, null, 'Rp. ');
                    }, className: "text-right"
                },
                {
                    "data": "jumlah_karton", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data);
                    }, className: "text-right"
                },
                {
                    "data": "jumlah_renceng", render(data, type, full, meta) {
                        return format_rupiah(data == null ? 0 : data);
                    }, className: "text-right"
                },
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
                { "data": "penerima" },
                { "data": "status_str" },
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
        hitung();
    }
    dynamic();

    // Setor
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>sales/penjualan/setor',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Setoran berhasil disimpan'
            })
            dynamic();
            data = data.data;
            window.location.href = '<?= base_url() ?>sales/penjualan/detailPenjualan/' + data.id_stok_keluar;
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Setoran gagal disimpan'
            })
        }).always(() => {
            $.LoadingOverlay("hide");
            $('#myModal').modal('toggle')
        })
    });


    $("#btn-filter").click(() => {
        const status = $('#filter-status').val();
        dynamic(status);
    });
})

function hitung() {
    $("#total_harga, #setoran").change(function () {
        var total_harga = $("#total_harga").val();
        var setoran = $("#setoran").val();
        var sisa = parseInt(total_harga) - parseInt(setoran);
        $("#sisa").val(sisa);
    })
    $("#total_harga, #setoran").keyup(function () {
        var total_harga = $("#total_harga").val();
        var setoran = $("#setoran").val();
        var sisa = parseInt(total_harga) - parseInt(setoran);
        $("#sisa").val(sisa);
    })

}