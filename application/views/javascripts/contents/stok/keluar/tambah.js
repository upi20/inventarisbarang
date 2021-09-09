let jumlah_produk = 0;
$(function () {
    // initial select2
    $('#sales').select2();

    function dynamic() {
        const table_html = $('#dt_basic');
        table_html.dataTable().fnDestroy();
        const tableUser = table_html.DataTable({
            "ajax": {
                "url": "<?= base_url()?>stok/keluar/tambah/ajax_data/",
                "data": {
                    id: $("#id_stok_keluar").val()
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
                { "data": "jumlah" },
                {
                    "data": "id", render(data, type, full, meta) {
                        return `<div class="pull-right">
									<button type="button" class="btn btn-primary btn-xs"
                                    data-id="${data}"
                                    data-id_produk="${full.id_produk}"
                                    data-jumlah="${full.jumlah}"
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
                targets: [0, 3]
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

    // tambah dan ubah
    $("#btn-sumbit-produk").click(function () {
        // validasi
        if ($("#produk").val() == '') {
            Toast.fire({
                icon: 'error',
                title: 'Produk Belum Dipilih'
            })
            $("#produk").focus();
            return;
        }

        if (Number($("#jumlah").val()) < 1) {
            Toast.fire({
                icon: 'error',
                title: 'Jumlah stok keluar produk minimal satu'
            })
            $("#jumlah").focus();
            return;
        }

        $('#card-body-form-tambah').LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/keluar/tambah/' + ($("#id").val() == "" ? 'insert' : 'update'),
            data: {
                id: $("#id").val(),
                id_stok_keluar: $("#id_stok_keluar").val(),
                produk: $('#produk').val(),
                jumlah: $("#jumlah").val(),
            },
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            dynamic();
            setInput();
        }).fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Data gagal disimpan'
            })
        }).always(() => {
            $('#card-body-form-tambah').LoadingOverlay("hide");
        })
    });

    $("#main-form").submit(function (ev) {
        ev.preventDefault();
        // validasi
        if (ev.originalEvent.submitter.id == 'btn-sumbit-produk') {
            return;
        }

        // dibayar
        if ($("#penanggung_jawab").val() == '') {
            Toast.fire({
                icon: 'error',
                title: 'Penganggung jawab belum dipilih'
            })
            $("#penanggung_jawab").focus();
            return;
        }

        if ($("#sales").val() == '') {
            Toast.fire({
                icon: 'error',
                title: 'Sales belum dipilih'
            })
            $("#sales").focus();
            return;
        }

        if (jumlah_produk < 1) {
            Toast.fire({
                icon: 'error',
                title: 'Jumlah produk minimal satu.'
            })
            return;
        }

        $(".card").LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/keluar/tambah/simpan',
            data: {
                id: $("#id_stok_keluar").val(),
                dibayar: $("#dibayar").val(),
                id_penanggung_jawab: $("#penanggung_jawab").val(),
                tanggal: $("#tanggal").val(),
                sales: $("#sales").val(),
                edit: global_edit,
            },
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil disimpan'
            })
            if (global_edit) {
                setTimeout(() => {
                    window.location.href = '<?= base_url() ?>stok/keluar/ListKeluar';
                }, 300);
            } else {
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }
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
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>stok/keluar/tambah/delete',
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

    $("#btn-reset").click(function () {
        setInput();
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
    const data = datas.dataset;
    setInput('ubah', data);
}

function setInput(type = 'tambah', data = {
    jumlah: '',
    id_produk: '',
    id: ''
}) {
    const btn = $("#btn-sumbit-produk");
    const text_title = $("#title-status");
    const btn_reset = $("#btn-reset");
    if (type == 'tambah') {
        btn.text("Tambah");
        btn.attr("class", "btn btn-success");
        text_title.attr("class", "text-success");
        text_title.html("Tambah Produk");
        btn_reset.hide();
    } else if (type == 'ubah') {
        btn.text("Ubah");
        btn.attr("class", "btn btn-primary");
        text_title.attr("class", "text-primary");
        text_title.html("Ubah Produk");
        btn_reset.show();
    }

    $("#jumlah").val(data.jumlah);
    $('#id').val(data.id);
    $('#produk').val(data.id_produk);
}