@extends('adminlte::page')

@section('title', ucfirst($title))

@section('content_header')

<h1>{{ ucfirst($title) }}</h1>
@stop

@section('content')
@can("create $title")
<button class="btn btn-sm btn-primary mb-2" id="btnAdd">Tambah Data</button>
@endcan
<table id="table" class="table table-hover table-sm responsive nowrap" style="width: 100%">
    <thead>
        <tr>
            <th width="3%">No</th>
            <th>Title</th>
            <th>Content</th>
            <th width="15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<!-- Modal untuk Create dan Edit -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah {{ ucfirst($title) }}</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control form-control-sm" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control form-control-sm" id="content" name="content" required></textarea>
                    </div>
                    <input type="hidden" id="id"> <!-- Hidden field for edit -->
                    <button type="submit" class="btn btn-sm btn-primary float-right" id="saveBtn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var table = $("#table").DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
                ajax: '{{ route($title.'.index') }}', // Route untuk ambil data
                columns: [
            // Kolom untuk nomor urut (akan ditambahkan otomatis)
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1; // Menambahkan nomor urut
                },
                orderable: false, // Tidak bisa diurutkan
                searchable: false // Tidak bisa dicari
            },
            { data: 'title', name: 'title' },
            { data: 'content', name: 'content' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        order: [[1, 'asc']]  // Set default urutan berdasarkan kolom kedua (title)
    });

            // Open Create Post Modal
            $('#btnAdd').on('click', function() {
                $('#frm')[0].reset(); // Reset form
                $('#id').val(''); // Clear hidden ID
                $('#modalLabel').text('Tambah'); // Change modal title
                $('#saveBtn').text('Simpan'); // Change button text
                $('#modal').modal('show'); // Show the modal
            });

            // Open Edit Post Modal
            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('{{ url($title) }}/' + id + '/edit', function(data) {
                    $('#id').val(data.id); // Set ID in hidden input
                    $('#title').val(data.title); // Set title in input
                    $('#content').val(data.content); // Set content in textarea
                    $('#modalLabel').text('Edit'); // Change modal title
                    $('#savePostBtn').text('Perbaharui'); // Change button text
                    $('#modal').modal('show'); // Show the modal
                });
            });

            // Save (Create or Update) Post
            $('#frm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var id = $('#id').val();
                var method = id ? 'PUT' : 'POST';
                var url = id ? '{{ url($title) }}/' + id : '{{ route($title.".store") }}';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {

                        if(response.icon=="success"){
                        $('#modal').modal('hide'); // Hide the modal
                        table.ajax.reload(); // Reload DataTable
                    }

                // Show SweetAlert2 notification
                Swal.fire({

                    icon: response.icon,
                    title: response.title,
                    text: response.message,
                    showConfirmButton: true,
                    timer: 1500
                });
            },
            error: function(xhr) {
                   // Pastikan server mengirimkan JSON error message dengan properti 'message'
                   let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan yang tidak diketahui.';
                   
                   Swal.fire({
                    icon: 'error',
                    title: 'Aduhh...',
                    text: errorMessage,
                    showConfirmButton: true,
                    timer: 1500
                });
               }
           });5
            });

            // Delete Post (Confirmation Modal)
            $(document).on('click', '.deleteBtn', function() {
                var id = $(this).data('id');
                 // SweetAlert2 Confirmation
                 Swal.fire({
                    title: 'Apa kamu yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus sekarang!',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
            // Jika tombol "Yes, delete it!" diklik
            $.ajax({
                url: '{{ url($title) }}/' + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Kirim CSRF Token
                },
                success: function(response) {
                    // Reload DataTable
                    table.ajax.reload();

                    // Show success notification with SweetAlert2
                    Swal.fire({
                        icon: response.icon,
                        title: response.title,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(xhr) {

                   // Pastikan server mengirimkan JSON error message dengan properti 'message'
                   let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan yang tidak diketahui.';

                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Aduhh...',
                        text: errorMessage,
                        showConfirmButton: true
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Jika tombol "No, keep it" diklik atau modal ditutup
            Swal.fire(
                'Dibatalkan',
                'Data anda aman!',
                'info'
                );
        }
    });
            });
        });
    </script>
    @endpush