@extends('layouts.app')

@section('title', 'Pengelolaan Uang Kas')

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
                <h1 class="text-primary">Pengelolaan Uang Kas</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-money-bill"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Saldo</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ number_format($saldo ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Kelola Uang Kas</h4></div>
                        <div class="card-body">
                            <a href="{{ route('download.pdf.uang-kas', ['pengelolaan' => 'uang-kas']) }}" class="btn btn-danger float-right mb-2">
                                <i class="fa-regular fa-file-pdf"></i>
                            </a>
                            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' && auth()->user()->pengurus->type == 'inti 6')
                            <button data-toggle="modal" data-target="#addUangKasModal" class="btn btn-success float-right mb-2 mr-2">
                                <i class="fa-regular fa-plus"></i>
                            </button>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Bulan</th>
                                            <th class="text-left">Pemasukan</th>
                                            <th class="text-left">Pengeluaran</th>
                                            <th>Saldo</th>
                                            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' && auth()->user()->pengurus->type == 'inti 6')
                                            <th class="text-right">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($uangKas as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center text-capitalize">{{ $item->bulan }}</td>
                                                <td class="text-left">Rp. {{ number_format($item->pemasukan, 0, ',', '.') }}</td>
                                                <td class="text-left">Rp. {{ number_format($item->pengeluaran, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($item->saldo, 0, ',', '.') }}</td>
                                                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pengurus' && auth()->user()->pengurus->type == 'inti 6')
                                                <td class="text-right">
                                                    <a id="btn-delete" href="{{ auth()->user()->role == 'admin' ? route('admin.pengelolaan.uang-kas.delete', $item->id) : route('pengurus.pengelolaan.uang-kas.delete', $item->id) }}" class="btn btn-danger">
                                                        <i class="fa-regular fa-trash"></i>
                                                    </a>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addUangKasModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Uang Kas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ auth()->user()->role == 'admin' ? route('admin.pengelolaan.uang-kas.tambah') : route('pengurus.pengelolaan.uang-kas.tambah') }}" class="needs-validation" novalidate=''
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bulan">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option>Pilih Bulan</option>
                                <option value="januari">Januari</option>
                                <option value="februari">Februari</option>
                                <option value="maret">Maret</option>
                                <option value="april">April</option>
                                <option value="mei">Mei</option>
                                <option value="juni">Juni</option>
                                <option value="juli">Juli</option>
                                <option value="agustus">Agustus</option>
                                <option value="september">September</option>
                                <option value="oktober">Oktober</option>
                                <option value="november">November</option>
                                <option value="desember">Desember</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pemasukan">Pemasukan</label>
                            <input type="text" name="pemasukan" id="pemasukan" class="form-control" autocomplete="off"
                                value="0">
                        </div>
                        <div class="form-group">
                            <label for="pengeluaran">Pengeluaran</label>
                            <input type="text" name="pengeluaran" id="pengeluaran" class="form-control" autocomplete="off"
                                value="0">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
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
            const table = $('#table-1').DataTable();

            const selectElements = document.querySelectorAll('select[name="bulan"]');
            const currentYear = new Date().getFullYear();

            selectElements.forEach(selectElement => {
                for (let i = 1; i < selectElement.options.length; i++) {
                    const option = selectElement.options[i];
                    option.value = `${option.value} ${currentYear}`;
                }
            });

            const handleDeleteUangKas = (e) => {
                e.preventDefault();
                const target = e.currentTarget.getAttribute("href");
                swal({
                    title: "Apakah Kamu Yakin?",
                    text: "Sekali menghapus, maka kamu tidak dapat mengembalikannya lagi",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location.href = target;
                        swal("Success, Uang Kas berhasil dihapus!", {
                            icon: "success",
                        });
                    } else {
                        swal("Penghapusan Uang Kas dibatalkan!");
                    }
                });
            }

            table.on('draw', () => {
                const editButtons = document.querySelectorAll('a[id="btn-delete"]');
                editButtons.forEach((button) => {
                    button.removeEventListener('click', handleDeleteUangKas);
                    button.addEventListener('click', handleDeleteUangKas);
                });
            });

            table.draw();
        });
    </script>
@endpush
