@extends('layouts.pdf')
@section('title', 'Daftar Anggota PMR')

@section('table')
    <table>
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>Nis</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggota as $item)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $item->nis }}
                    </td>
                    <td>
                        {{ $item->user->fullname }}
                    </td>
                    <td>
                        {{ $item->kelas->name }}
                    </td>
                    <td>
                        {{ $item->unit->name }}
                    </td>
                    <td>
                        <div style="text-transform: capitalize">{{ $item->status }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

