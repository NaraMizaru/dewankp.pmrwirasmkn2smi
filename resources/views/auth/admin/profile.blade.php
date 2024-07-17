@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Profile | {{ $user->fullname }}</h1>
            </div>
            <div class="section-body">
                @if (Session::has('status'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('status') }}
                    </div>
                @else
                    <h2 class="section-title">Hi, {{ $user->username }}!</h2>
                    <p class="section-lead">
                        Change information about yourself on this page.
                    </p>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="{{ route('admin.edit.profile', $user->username) }}" method="POST"
                                enctype="multipart/form-data" class="needs-validation" novalidate=''>
                                @csrf
                                <div class="card-body mt-5">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            @if ($user->profile_image == null)
                                                <img src="{{ asset('img/avatar/avatar-1.png') }}" width="150"
                                                    height="150" alt="" class="rounded-circle mr-2"
                                                    id="profile-image">
                                            @else
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" width="150"
                                                    height="150" alt="" class="rounded-circle mr-2"
                                                    id="profile-image">
                                            @endif
                                            <input name="profile_image" type="file" class="d-none" id="image-input" />
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
                                                <label for="" class="form-label">Username</label>
                                                <input type="text" class="form-control" value="{{ $user->username }}"
                                                    name="username">
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
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type='submit' class="btn btn-primary float-right mb-5">Simpan
                                        Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
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
