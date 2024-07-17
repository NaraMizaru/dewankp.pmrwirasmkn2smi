@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="card card-warning">
        <div class="card-header">
            <h4 class="text-warning">Form Pendaftaran Anggota PMR</h4>
        </div>

        <div class="card-body">
            @if (Session::has('error'))
                <div class="form-group">
                    <div class="alert alert-danger text-center" role="alert">
                        {{ Session::get('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="needs-validation" novalidate=''>
                @csrf
                <div class="form-group">
                    <label for="fullname">Name Lengkap</label>
                    <input id="fullname" type="text" class="form-control" name="fullname" autofocus required>
                </div>
                <div class="form-group">
                    <label for="nis">Nis</label>
                    <input id="nis" type="text" class="form-control" name="nis" autofocus required>
                </div>
                <div class="form-group">
                    <label for="username" class="control-label">Username</label>
                    <input id="username" type="text" class="form-control" name="username" autofocus required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="text" class="form-control" name="email" autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="d-block">Password</label>
                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator"
                        name="password" required>
                    <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="no_telp">Nomor Whatsapp</label>
                    <input id="no_telp" type="text" class="form-control" name="no_telp" autofocus required>
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <select class="form-control" name="kelas_id" required>
                        <option>Pilih Kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Bidang</label>
                    <select class="form-control" name="bidang_id">
                        <option>Pilih Bidang</option>
                        @foreach ($bidang as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                        <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                    </div>
                </div> --}}

                <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
