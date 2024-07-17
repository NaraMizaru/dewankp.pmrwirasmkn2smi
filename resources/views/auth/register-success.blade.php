@extends('layouts.auth')

@section('title', 'Register Success')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="card card-warning">
        <div class="card-header">
            @if (Session::has('success'))
              <h4 class="text-warning">{{ Session::get('success') }}</h4>
            @endif
        </div>

        <div class="card-body">
          <p class="fw-bold text-center">Selamat anda telah menjadi anggota PMR Wira SMK Negeri 2 Sukabumi, klik button dibawah untuk masuk kedalam grup</p>
          <div class="d-flex align-items-center justify-content-center">
            <a href="{{ $link->value }}" target="_blank" class="btn btn-warning text-center">Masuk Grup</a>
          </div>
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
