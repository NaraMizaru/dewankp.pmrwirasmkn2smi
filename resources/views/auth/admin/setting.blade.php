@extends('layouts.app')

@section('title', 'Settings')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Settings | PMR Wira SMK Negeri 2 Sukabumi</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-center" role="alert">{{ Session::get('status') }}</div>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Daftar Pengaturan</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($setting as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="text-capitalize">{{ $item->name }}</td>
                                                    <td>
                                                        {{ $item->value }}
                                                    </td>
                                                    <td>
                                                        @if ($item->value != 'reset')
                                                            <button class="btn btn-warning" data-id='{{ $item->id }}'
                                                                data-type="{{ $item->type }}"
                                                                data-name="{{ $item->name }}" data-target="#editModal"
                                                                data-toggle="modal" id="btn-edit"><i
                                                                    class="far fa-pen-to-square"></i></button>
                                                        @else
                                                            <button class="btn btn-danger" data-type="{{ $item->type }}" data-target="#resetModal" data-toggle="modal"><i class="fa fa-fw fa-stop"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <form enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize">Register</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="resetModal">
        <div class="modal-dialog" role="document">
            <form method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize">Reset Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin mereset?</p>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = $('#table-1').DataTable();

            const handleEditButton = (e) => {
                const button = e.currentTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const type = button.getAttribute('data-type');

                const modal = document.getElementById('editModal');
                const modalTitle = modal.querySelector('.modal-title');
                const modalBody = modal.querySelector('.modal-body');
                const form = modal.querySelector('form');

                modalTitle.textContent = `${name}`;
                let formAction = `{{ route('admin.dashboard.setting.edit', ':id') }}`
                form.setAttribute('action', formAction.replace(':id', id))

                if (type == 'text') {
                    modalBody.innerHTML = `
                        <label for="value">Value</label>
                        <input type="text" name="value" id="value" class="form-control">
                    `
                }

                if (type == 'select') {
                    modalBody.innerHTML = `
                        <label for="value">Value</label>
                        <select name="value" id="value" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    `
                }

                if (type == 'image') {
                    modalBody.innerHTML = `
                        <label for="value">Value</label>
                        <input type="file" name="value" id="value" class="form-control">
                    `
                }

            }

            const handleResetButton = (e) => {
                const button = e.currentTarget;
                const type = button.getAttribute('data-type');

                const modal = document.getElementById('resetModal');
                const form = modal.querySelector('form');

                let formAction;
                if (type == 'anggota') {
                    formAction = '{{ route("admin.dashboard.setting", ["reset" => "anggota"]) }}'
                }

                form.setAttribute('action', formAction);
            }

            const editButton = document.querySelectorAll('button[data-target="#editModal"]');
            editButton.forEach((button) => {
                button.removeEventListener('click', handleEditButton);
                button.addEventListener('click', handleEditButton);
            });

            const resetButton = document.querySelectorAll('button[data-target="#resetModal"]');
            resetButton.forEach((button) => {
                button.removeEventListener('click', handleResetButton);
                button.addEventListener('click', handleResetButton);
            });
        })
    </script>
@endpush
