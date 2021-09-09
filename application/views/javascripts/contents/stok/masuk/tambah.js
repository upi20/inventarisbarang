let jumlah_produk = 0;
const data_satuan_harga = new Map();
let jumlah_detail_sebelumnya = 0;
$(function () {
    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy();
        var tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>stok/masuk/tambah/ajax_data/",
                "data": {
                    id: $("#kode").val()
                },
                "type": 'POST',
                "dataSrc": function (json) {
                    jumlah_produk = json.data.length;
                    return json.data;
                }
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "columns": [
                { "data": null },
                { "data": "produk" },
                {
                    "data": "harga", render(data, type, full, meta) {
                        return format_rupiah(data, false);
                    }, className: "text-right"
                },
                {
                    "data": "jumlah", render(data, type, full, meta) {
                        return format_rupiah(data, false);
                    }, className: "text-right"
                },
                {
                    "data": "ttl_harga", render(data, type, full, meta) {
                        return format_rupiah(data, false);
                    }, className: "text-right"
                },
                // { "data": "satuan" },
                // {
                //     "data": "updated_at", render(data, type, full, meta) {
                //         return data == null || data == '' ? full.created_at : full.updated_at;

                //     }, className: "nowrap"
                // },
                {
                    "data": "id", render(data, type, full, meta) {
                        return `<div class="pull-right">
									<button type="button" class="btn btn-primary btn-xs"
                                    data-id="${data}"
                                    data-id_produk="${full.id_produk}"
                                    data-id_satuan_harga="${full.id_satuan_harga}"
                                    data-jumlah="${full.jumlah}"
                                    data-harga="${full.harga}"
                                    data-ttl_harga="${full.ttl_harga}"
                                    onclick="Ubah(this)">
										<i class="fa fa-edit"></i> Ubah
									</button>
									<button type="button" class="btn btn-danger btn-xs" onclick="Hapus(${data})">
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

    $("#btn-tambah").click(() => {
        $("#myModalLabel").text("Tambah Produk Stok Masuk");
        $('#id').val("");
        $('#jumlah').val("0");
        $('#harga').val("Rp");
        $('#ttl_harga').val("0");
        $('#ttl_harga_text').val("Rp");
        $('#harga_text').val("Rp");
        $('#produk').val('');
        $('#satuan_harga').val('');

        // cek darkmode
        if (localStorage.getItem('isDarkMode') == 'false') {
            $('.form-control-plaintext').attr('style', 'color: dark;');
        } else {
            $('.form-control-plaintext').attr('style', 'color: white;');
        }

    });

    // tambah dan ubah
    $("#form-produk").submit(function (ev) {
        ev.preventDefault();

        if (Number($("#jumlah").val()) < 1) {
            Toast.fire({
                icon: 'error',
                title: 'Jumlah stok masuk produk minimal satu'
            })
            $("#jumlah").focus();
            return;
        }

        // validasi
        $("#myModal").find('.modal-content').LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/masuk/tambah/' + ($("#id").val() == "" ? 'insert' : 'update'),
            data: {
                id: $("#id").val(),
                id_stok_masuk: $("#id_stok_masuk").val(),
                produk: $('#produk').val(),
                satuan_harga: $('#satuan_harga').val(),
                harga: $("#harga").val(),
                jumlah: $("#jumlah").val(),
                ttl_harga: $("#ttl_harga").val(),
                satuan: $("#satuan").val(),
                jml_baru: $("#jumlah").val()
            },
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
            $("#myModal").find('.modal-content').LoadingOverlay("hide");
            $('#myModal').modal('toggle')
            refreshTotalHarga();
        })
    });

    $("#main-form").submit(function (ev) {
        ev.preventDefault();
        // validasi
        // dibayar
        if ($("#penanggung_jawab").val() == '') {
            Toast.fire({
                icon: 'error',
                title: 'Penganggung jawab belum dipilih'
            })
            $("#penanggung_jawab").focus();
            return;
        }

        if (jumlah_produk < 1) {
            Toast.fire({
                icon: 'error',
                title: 'Jumlah produk minimal satu.'
            })
            $("#btn-tambah").click();
            return;
        }

        // jumlah produk
        // if (Number($("#dibayar").val()) < 1) {
        //     Toast.fire({
        //         icon: 'error',
        //         title: 'Jumlah dibayar tidak boleh kosong'
        //     })
        //     $("#dibayar_text").val('');
        //     $("#dibayar_text").focus();
        //     return;
        // }

        $(".card").LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/masuk/tambah/simpan',
            data: {
                id: $("#kode").val(),
                dibayar: $("#dibayar").val(),
                id_penanggung_jawab: $("#penanggung_jawab").val(),
                edit: global_edit
            },
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            setTimeout(() => {
                window.location.href = '<?= base_url() ?>stok/masuk/ListMasuk';
            }, 300)
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $(".card").LoadingOverlay("hide");
        })
    })

    // hapus
    $('#OkCheck').click(() => {
        let id = $("#idCheck").val()
        $('#ModalCheck').find('.modal-content').LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/masuk/tambah/delete',
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
            $('#ModalCheck').find('.modal-content').LoadingOverlay("hide");
            refreshTotalHarga();
        })
    })

    // tambah jumlah keyup
    $("#jumlah").keyup(function () {
        const harga = data_satuan_harga.get($("#satuan_harga").val());
        const jumlah = Number(this.value);
        const jumlah_total = Number(harga * jumlah);

        $("#ttl_harga").val(jumlah_total);
        $("#harga_text").val("Rp " + format_rupiah(harga, false));
        $("#ttl_harga_text").val("Rp " + format_rupiah(jumlah_total, false));
        $("#jumlah_text").val("Rp " + format_rupiah(jumlah, false));
    })

    $("#jumlah").change(function () {
        const harga = data_satuan_harga.get($("#satuan_harga").val());
        const jumlah = Number(this.value);
        const jumlah_total = Number(harga * jumlah);

        $("#ttl_harga").val(jumlah_total);
        $("#harga_text").val("Rp " + format_rupiah(harga, false));
        $("#ttl_harga_text").val("Rp " + format_rupiah(jumlah_total, false));
        $("#jumlah_text").val("Rp " + format_rupiah(jumlah, false));
    })

    $("#satuan_harga").change(function () {
        const harga = data_satuan_harga.get(this.value);
        const jumlah = Number($("#jumlah").val());
        const jumlah_total = Number(harga * jumlah);
        $("#harga").val(harga);
        $("#ttl_harga").val(jumlah_total);
        $("#jumlah").val(jumlah);
        $("#harga_text").val("Rp " + format_rupiah(harga, false));
        $("#ttl_harga_text").val("Rp " + format_rupiah(jumlah_total, false));
        $("#jumlah_text").val("Rp " + format_rupiah(jumlah, false));
    })

    $("#dibayar_text").val("Rp " + format_rupiah($("#dibayar_text").val(), false));
    $("#dibayar_text").focusin(function () {
        $(this).val($("#dibayar").val());
        $(this).removeClass('text-right');
        $(this).attr('type', 'number');
    })

    $("#dibayar_text").focusout(function () {
        $(this).attr('type', 'text');
        $(this).addClass('text-right');
        $(this).val("Rp " + format_rupiah(this.value, false));
        refreshInput();
    })

    $("#dibayar_text").keyup(function () {
        const sisa = Number($("#total_harga").val()) - Number(this.value);
        $("#dibayar").val(this.value);
        $("#sisa").val(sisa);
        $("#sisa_text").val("Rp " + format_rupiah(sisa, false));
    })

    // initital select
    // produk
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>stok/masuk/tambah/ajax_list_satuan_produk',
        data: null
    }).done((data) => {
        let html = '<option value selected>Pilih Produk</option>';
        data.forEach(e => {
            html += `<option value="${e.id}">${e.text}</option>`;
        });
        $("#produk").html(html);
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data.'
        })
    })

    // Satuan Harga
    $.ajax({
        method: 'get',
        url: '<?= base_url() ?>stok/masuk/tambah/ajax_list_satuan_harga',
        data: null
    }).done((data) => {
        let html = '<option value selected>Pilih Satuan Harga</option>';
        data.forEach(e => {
            data_satuan_harga.set(e.id, Number(e.harga));
            html += `<option value="${e.id}">${e.text}</option>`;
        });
        $("#satuan_harga").html(html);
    }).fail(($xhr) => {
        Toast.fire({
            icon: 'error',
            title: 'Gagal mendapatkan data.'
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
const Ubah = (datas) => {
    data = datas.dataset;
    $("#myModalLabel").text("Ubah stok masuk");
    // cek darkmode
    if (localStorage.getItem('isDarkMode') == 'false') {
        $('.form-control-plaintext').attr('style', 'color: dark;');
    } else {
        $('.form-control-plaintext').attr('style', 'color: white;');
    }
    $("#myModal").modal("toggle");
    $('#id').val(data.id);
    $('#produk').val(data.id_produk);
    $('#satuan_harga').val(data.id_satuan_harga);
    $("#harga").val(data.harga);
    $("#jumlah").val(data.jumlah);
    jumlah_detail_sebelumnya = Number(data.jumlah);
    $("#ttl_harga").val(data.ttl_harga);
    $("#harga_text").val("Rp " + format_rupiah(data.harga, false));
    $("#ttl_harga_text").val("Rp " + format_rupiah(data.ttl_harga, false));
}



function refreshTotalHarga() {
    $.ajax({
        method: 'get',
        url: '<?= base_url(); ?>stok/masuk/tambah/getTotalHarga',
        data: {
            idStokMasuk: $("#kode").val()
        },
    }).done((data) => {
        $("#total_harga").val(data.total);
        $("#total_harga_text").val("Rp " + format_rupiah(data.total, false));
        cekSisa()
    }).fail(($xhr) => {
        setToast('danger', 'danger', 'Failed', 'Gagal mendapatkan data total harga');
    })
}

function cekSisa() {
    const total = Number($("#total_harga").val());
    const dibayar = Number($("#dibayar").val());
    const sisa = total - dibayar;
    $("#sisa").val(sisa);
    $("#sisa_text").val("Rp " + format_rupiah(sisa, false));
}

function refreshInput() {
    var harga = $("#harga").val();
    var jumlah = $("#jumlah").val();
    var total1 = Number(harga) * Number(jumlah);
    $("#ttl_harga").val(total1);
}

cekSisa();
refreshTotalHarga();