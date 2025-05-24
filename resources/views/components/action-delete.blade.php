<!-- resources/views/posts/partials/actions.blade.php -->

@can("delete $title")
<!-- Mengganti form dengan tombol delete yang akan diproses menggunakan SweetAlert2 -->
<button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $row->id }}">Hapus</button>
@endcan
