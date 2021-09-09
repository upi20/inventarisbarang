$(() => {
    // Fungsi cari
    $('#sekolah').select2({
        ajax: {
            url: '<?= base_url() ?>sekolah/cari',
            dataType: 'json',
            method: 'post',
            data: function (params) {
                return {
                    q: params.term
                };
            },
        },
        dropdownParent: $(".card-body"),
        minimumInputLength: 1,
    })

    $('#sekolah').on('select2:select', function (e) {
        const id = $(this).select2('data')[0].id;
        if (id) {
            $('#kelas').html();
            $.ajax({
                method: 'get',
                url: '<?= base_url() ?>sekolah/cari/getKelas',
                data: {
                    id_sekolah: id
                },
            }).done((data) => {
                $('#kelas').empty();
                data.results.forEach(function (e) {
                    $('#kelas').append($('<option>', { value: e.id, text: e.text }))
                })
            }).fail(($xhr) => {
                console.log($xhr);
            })
        } else {
            $('#kelas').empty();
        }
    });

    // cek nip
    $("#nisn").change(function () {
        $.ajax({
            method: 'get',
            url: '<?= base_url() ?>sekolah/cari/cekNisn',
            data: {
                nisn: this.value
            },
        }).done((data) => {
            if (data.data > 0) {
                setToast({ title: "Gagal", body: 'NISN Sudah Terdaftar', class: "bg-warning" });
                this.value = '';
                this.focus();
            }
        }).fail(($xhr) => {
            console.log($xhr);
        })
    });

    $('#password-visibility').change(function () {
        const password = $('#password')
        const password1 = $('#password1')

        // password toggle
        if (this.checked) {
            password.attr('type', 'text')
            password1.attr('type', 'text')
        } else {
            password.attr('type', 'password')
            password1.attr('type', 'password')
        }
    })

    $('#password1').change(function () {
        if (this.value != $('#password').val()) {
            setToast({ title: "Gagal", body: 'Ulangi Password Tidak Sama', class: "bg-warning" });
            this.value = '';
            this.focus();
        }
    });

    $('#password').change(function () {
        if (this.value.length < 6) {
            setToast({ title: "Gagal", body: 'Pajang karakter password minimal 6', class: "bg-warning" });
            this.value = '';
            this.focus();
        }
    });

    // submit
    $("#form-registrasi").submit(function (e) {
        e.preventDefault();
        $.LoadingOverlay("show");
        $.ajax({
            method: 'post',
            url: '<?= base_url() ?>registrasi/insert_siswa',
            data: {
                nisn: $('#nisn').val(),
                nama: $('#nama').val(),
                tanggal_lahir: $('#tanggal_lahir').val(),
                jenis_kelamin: $('#jenis_kelamin').val(),
                alamat: $('#alamat').val(),
                password: $('#password').val(),
                no_telpon: $('#no_telpon').val(),
                id_sekolah: $('#sekolah').select2('data')[0].id,
                id_kelas: $('#kelas').val()
            },
        }).done((data) => {
            if (data.data > 0) {
                setToast({ title: "Berhasil", body: "Registrasi berhasil. Silahakan tunggu akun di konfirmasi oleh guru sekolah.", class: "bg-info" });
            }
            $('#nisn').val('');
            $('#nama').val('');
            $('#tanggal_lahir').val('');
            $('#alamat').val('');
            $('#password').val('');
            $('#password1').val('');
            $('#no_telpon').val('');
            $('#sekolah').empty();
            $('#kelas').empty();
        }).fail(($xhr) => {
            // console.log($xhr);
            setToast({ title: "Gagal", body: "Registrasi Gagal segera hubungi administrator.", class: "bg-danger" });
        }).always(() => {
            $.LoadingOverlay("hide");
        })
    })
})

function setToast(data) {
    $(document).Toasts('create', {
        class: data.class,
        title: data.title,
        body: data.body
    })
    setTimeout(() => $("#toastsContainerTopRight").remove(), 5000);
}


