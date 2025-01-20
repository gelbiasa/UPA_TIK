<form action="{{ url('/indukMenu/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Induk Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Input -->
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <small id="error-name" class="error-text form-text text-danger"></small>
                </div>
                <!-- Slug Input -->
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" required>
                    <small id="error-slug" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        // Validasi form
        $("#form-tambah").validate({
            rules: {
                name: { required: true, minlength: 3, maxlength: 100 },
                slug: { required: true, minlength: 3, maxlength: 100 }
            },
            messages: {
                name: {
                    required: "Nama harus diisi.",
                    minlength: "Nama minimal terdiri dari 3 karakter.",
                    maxlength: "Nama maksimal 100 karakter."
                },
                slug: {
                    required: "Slug harus diisi.",
                    minlength: "Slug minimal terdiri dari 3 karakter.",
                    maxlength: "Slug maksimal 100 karakter."
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataIndukMenu.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
