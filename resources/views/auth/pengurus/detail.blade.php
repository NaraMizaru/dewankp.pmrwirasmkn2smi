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
                        <form action="">
                            <div class="card-body">
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
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" value="{{ $user->fullname }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Nis</label>
                                            <input type="text" class="form-control" value="{{ $pengurus->nis }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Username</label>
                                            <input type="text" class="form-control" value="{{ $user->username }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Password</label>
                                            <input type="text" class="form-control" value="{{ $user->password }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Email</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                                                <a href="{{ 'mailto:' . $user->email }}" target="_blank" class="btn btn-danger d-flex align-items-center"><i class="far fa-envelope" ></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">No Telepon</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="phoneNumber" value="{{ $user->no_telp }}" disabled>
                                                <a href="" target="_blank" class="btn btn-success d-flex align-items-center" id="linkWhatsapp"><i class="fa-brands fa-whatsapp" ></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Kelas</label>
                                            <select name="" id="" class="form-control" disabled>
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
                                            <select name="" id="" class="form-control" disabled>
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
                                            <select name="" id="" class="form-control" disabled>
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
                                            <label for="" class="form-label">Inti</label>
                                            <select name="" id="" class="form-control" disabled>
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
    <script>
        const phoneNumberInput = document.getElementById('phoneNumber').value
        const whatsappLink = document.getElementById('linkWhatsapp')
        let phoneNumber = phoneNumberInput.trim()

        if (phoneNumber.startsWith('08')) {
            phoneNumber = '62' + phoneNumber.slice(1)
        }

        whatsappLink.setAttribute('href', `https://wa.me/${phoneNumber}`)
    </script>
@endpush
