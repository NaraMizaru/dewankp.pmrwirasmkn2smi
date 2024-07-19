@extends('layouts.app')

@section('title', 'Detail Pengurus')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Detail | {{ $user->fullname }}</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-primary">Profile</h3>
                        </div>
                        <form action="{{ route('admin.pengurus.edit', $user->username) }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate="">
                            @csrf
                            <div class="card-body">
                                @if (Session::has('success'))
                                    <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                                @endif
                                <div class="row">
                                    <div class="col-12 text-center">
                                        @if ($user->profile_image == null)
                                            <img src="{{ asset('img/avatar/avatar-1.png') }}" width="150" height="150"
                                                alt="" class="rounded-circle mr-2" id="profile-image">
                                        @else
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" width="150"
                                                height="150" alt="" class="rounded-circle mr-2"
                                                id="profile-image">
                                        @endif
                                        <input type="file" class="d-none" name="profile_image" id="image-input" />
                                        <button type="button" class="btn btn-primary btn-sm" id="upload-button">Ganti
                                            Profile</button>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" value="{{ $user->fullname }}"
                                                name="fullname">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Nis</label>
                                            <input type="text" class="form-control" value="{{ $pengurus->nis }}"
                                                name="nis">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Username</label>
                                            <input type="text" class="form-control" value="{{ $user->username }}"
                                                name="username">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Password</label>
                                            <input type="text" class="form-control" value="{{ $user->password }}"
                                                name="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Email</label>
                                            <input type="text" class="form-control" value="{{ $user->email }}"
                                                name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">No Telepon</label>
                                            <input type="text" class="form-control" value="{{ $user->no_telp }}"
                                                name="no_telp">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Kelas</label>
                                            <select id="" class="form-control" name="kelas_id">
                                                @foreach ($kelas as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $pengurus->kelas_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Unit</label>
                                            <select id="" class="form-control" name="unit_id">
                                                @foreach ($unit as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $pengurus->unit_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Bidang</label>
                                            <select id="" class="form-control" name="bidang_id">
                                                @foreach ($bidang as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $pengurus->bidang_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="inti" class="form-label">Inti</label>
                                            <select name="inti" id="inti" class="form-control">
                                                <option value="inti 6"
                                                    {{ $user->pengurus->type == 'inti 6' ? 'selected' : '' }}>Inti 6
                                                </option>
                                                <option value="inti 14"
                                                    {{ $user->pengurus->type == 'inti 14' ? 'selected' : '' }}>Inti 14
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right mb-3">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script>
        let profileImage = document.getElementById('profile-image');
        const uploadButton = document.getElementById('upload-button');
        const imageInput = document.getElementById('image-input');

        uploadButton.addEventListener('click', () => {
            imageInput.click();
        });

        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileImage.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
