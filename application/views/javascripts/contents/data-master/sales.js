$(document).ready(function () {
    const $table = $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        "buttons": ["csv", "excel", "colvis"]
    });

    $table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    // Add Row
    const addRow = (data) => {
        let row = [
            data.level,
            data.username,
            data.nama,
            data.telepon,
            data.deleted_at != null ? 'Di Hapus' : data.status == 0 ? 'Tidak Aktif' : (data.status == 1 ? 'Aktif' : (data.status == 2 ? 'Pending' : '')),
            data.deleted_at != null ? data.deleted_at : (data.update_at == null ? data.created_at : updated_at),
            data.deleted_at != null ? '' :
                '<div>'
                + '<button class="btn btn-primary btn-sm mr-1" onclick="Ubah(' + data.id + ')"><i class="fa fa-edit"></i> Ubah</button>'
                + '<button class="btn btn-danger btn-sm" onclick="Hapus(' + data.id + ')"><i class="fa fa-trash"></i> Hapus</button>'
                + '</div>'
        ]

        let $node = $($table.row.add(row).draw().node())
        $node.attr('data-id', data.id)
    }

    // Edit Row
    const editRow = (id, data) => {
        let row = $table.row('[data-id=' + id + ']').index()

        $($table.row(row).node()).attr('data-id', id)
        $table.cell(row, 0).data(data.level)
        $table.cell(row, 1).data(data.username)
        $table.cell(row, 2).data(data.nama)
        $table.cell(row, 3).data(data.telepon)
        $table.cell(row, 4).data(data.deleted_at != null ? 'Di Hapus' : data.status == 0 ? 'Tidak Aktif' : (data.status == 1 ? 'Aktif' : (data.status == 2 ? 'Pending' : '')))
        $table.cell(row, 5).data(data.deleted_at != null ? data.deleted_at : (data.update_at == null ? data.created_at : updated_at))
    }

    // Delete Row
    const deleteRow = (id) => {
        $table.row('[data-id=' + id + ']').remove().draw()
    }

    // Ulang password
    $('#upassword').on('change', () => {
        let password = $('#password').val()
        let upassword = $('#upassword').val()

        if (upassword != password) {
            Toast.fire({
                icon: 'error',
                title: 'Password tidak sama.'
            })
            $('#submit').prop('disabled', 'disabled')
        }
        else {
            Toast.fire({
                icon: 'success',
                title: 'Password sama.'
            })
            $('#submit').prop('disabled', false)
        }
    })

    // Fungsi simpan
    $('#form').submit((ev) => {
        ev.preventDefault()

        let id = $('#id').val()
        let level = $('#level').val()
        let nama = $('#nama').val()
        let telepon = $('#phone').val()
        let username = $('#username').val()
        let password = $('#password').val()

        if (id == 0) {
            if ($('#password').val() == '') {
                Toast.fire({
                    icon: 'error',
                    title: 'Password tidak harus di isi.'
                })
                $('#password').focus()
                return;
            }
            // Insert
            $.LoadingOverlay("show");
            $.ajax({
                method: 'post',
                url: '<?= base_url() ?>data-master/sales/insert',
                data:
                {
                    level: level,
                    nama: nama,
                    telepon: telepon,
                    username: username,
                    password: password,
                }
            }).done((data) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil ditambahkan.'
                })
                location.reload();
                // addRow(data)
            })
                .fail(($xhr) => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal ditambahkan.'
                    })
                }).
                always(() => {
                    $('#myModal').modal('toggle')
                    $.LoadingOverlay("hide");
                })
        }
        else {

            // Update
            $.LoadingOverlay("show");
            $.ajax({
                method: 'post',
                url: '<?= base_url() ?>data-master/sales/update',
                data:
                {
                    id: id,
                    level: level,
                    nama: nama,
                    telepon: telepon,
                    username: username,
                    password: password
                }
            }).done((data) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil diubah.'
                })
                location.reload();
                // editRow(id, data)

            })
                .fail(($xhr) => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal Diubah..'
                    })
                }).
                always(() => {
                    $('#myModal').modal('toggle')
                    $.LoadingOverlay("hide");
                })
        }
    })

    // Fungsi Delete
    $('#OkCheck').click(() => {
        let id = $("#idCheck").val()
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>data-master/sales/delete',
            data: {
                id: id
            }
        }).done((data) => {
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil dihapus'
            })
            location.reload();
            // dynamic();
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

    // Clik Tambah
    $('#btn-tambah').on('click', () => {
        $('#myModalLabel').html('Tambah Sales')
        $('#id').val('')
        $('#level').val('6')
        $('#nama').val('')
        $('#phone').val('')
        $('#username').val('')
        $('#password').val('')
        $('#upassword').val('')
        $('#status').val('')

        $('#myModal').modal('toggle')
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
    // $.LoadingOverlay("show");
    window.apiClient.pengaturanPengguna.detail(id)
        .done((data) => {

            $('#myModalLabel').html('Ubah Sales')
            $('#id').val(data.id)
            $('#level').val(data.level)
            $('#nama').val(data.nama)
            $('#phone').val(data.phone)
            $('#username').val(data.username)
            $('#status').val(data.status)
            $('#myModal').modal('toggle')
        })
        .fail(($xhr) => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal mendapatkan data.'
            })
        })
        .always(() => {
            // $.LoadingOverlay("hide");
        })
}