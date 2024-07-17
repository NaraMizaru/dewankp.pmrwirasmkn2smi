@extends('layouts.app')

@section('title', 'Join Pemilihan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Daftar Kandidat | {{ $pemilu->name }}</h1>
            </div>
            <div class="row">
                @if (Session::has('status'))
                    <div class="col-12">
                        <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                    </div>
                @endif
                @foreach ($kandidat as $item)
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="card" style="height: 100%">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h4>{{ $item->description }}</h4>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <button data-target="#visiMisiModal" data-toggle="modal" class="btn btn-primary btn-sm"
                                        data-visi-misi="{{ $item->visi_misi }}">Visi
                                        Misi</button>
                                    <button class="btn btn-warning btn-sm" data-target="#voteModal" data-toggle="modal"
                                        data-id='{{ $item->id }}' data-role="{{ auth()->user()->role }}"
                                        data-slug="{{ $pemilu->slug }}">Vote</button>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <img src="{{ asset('/storage/' . $item->gambar) }}" alt="" class="card-img-top"
                                    style="height: 300px">
                            </div>
                            <div class="card-footer">
                                <h6 class="text-center text-primary">{{ $item->name }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="visiMisiModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Visi Misi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="visiMisiParagraph"></p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="voteModal">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin memilih kandidat ini?</p>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
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
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const handleVisiMisi = (e) => {
                const button = e.currentTarget
                const visiMisi = button.getAttribute('data-visi-misi')

                const modal = document.getElementById('visiMisiModal')
                const paragraph = modal.querySelector('#visiMisiParagraph')
                paragraph.innerText = visiMisi
            }

            const handleVote = (e) => {
                const button = e.currentTarget
                const id = button.getAttribute('data-id')
                const role = button.getAttribute('data-role')
                const slug = button.getAttribute('data-slug');

                const modal = document.getElementById('voteModal')
                const form = modal.querySelector('form')

                let formAction;
                if (role == 'admin') {
                    formAction = "{{ route('admin.dashboard.pemilu.pemilihan.vote', ':slug') }}?kandidat_id=" + id
                } else if (role == 'pengurus') {
                    formAction = "{{ route('pengurus.dashboard.pemilu.pemilihan.vote', ':slug') }}?kandidat_id=" + id
                } else if (role == 'anggota') {
                    formAction = "{{ route('anggota.dashboard.pemilu.pemilihan.vote', ':slug') }}?kandidat_id=" + id
                }

                form.setAttribute('action', formAction.replace(':slug', slug))
            }

            const visiMisiButton = document.querySelectorAll('button[data-target="#visiMisiModal"]');
            visiMisiButton.forEach((button) => {
                button.removeEventListener('click', handleVisiMisi);
                button.addEventListener('click', handleVisiMisi);
            });

            const voteButton = document.querySelectorAll('button[data-target="#voteModal"]');
            voteButton.forEach((button) => {
                button.removeEventListener('click', handleVote);
                button.addEventListener('click', handleVote);
            });
        })
    </script>
@endpush
