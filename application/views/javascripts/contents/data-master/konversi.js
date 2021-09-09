$(function () {
    function dynamic() {
    $.ajax({
        method : 'post',
        url: '<?= base_url() ?>data-master/konversi/getData',
        data: form,
        cache: false,
        contentType: false,
        processData: false,
    }).done((data) => {
        if (data.data) {
            data = data.data;
            $('#id').val(data.id);
            $('#karton').val(data.karton);
            $('#renceng').val(data.renceng);
            $('#status').val(data.status);
            $('#tanggal').val(data.updated_at == null || data == '' ? data.created_at : data.updated_at);
            $('#simpan').text('Simpan');
        }
    }).fail(($xhr) => {
        $('#id').val("");
        $('#karton').val("");
        $('#renceng').val("");
        $('#status').val("");
        $('#tanggal').val("");
        $('#simpan').text('Tambah');
    })

    }
    dynamic();

    $('#reset').click(function(){   
    dynamic();
    })

    // tambah dan ubah
    $("#form").submit(function (ev) {
        ev.preventDefault();
        const form = new FormData(this);
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>data-master/konversi/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
        })
    });
})
