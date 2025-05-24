@extends('adminlte::page')

@section('title', 'Edit Profil')

@section('content_header')
    <h1>Edit Profil</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name', $user->name) }}" required>
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email / Username</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <hr>
        <h5>Ubah Password (opsional)</h5>

        <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password">
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
@stop
