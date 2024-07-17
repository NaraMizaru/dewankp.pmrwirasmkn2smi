@extends('layouts.pdf')
@section('title', 'Daftar Anggota PMR | ' . $thiss->name)

@section('table')
    <table>
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>Nis</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Password</th>
                @if ($by == 'kelas')
                    <th>Kelas</th>
                @endif
                @if ($by == 'unit')
                    <th>Unit</th>
                @endif
                @if ($by == 'bidang')
                    <th>Bidang</th>
                @endif
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
                        {{ $item->user->username }}
                    </td>
                    <td>
                        {{ $item->user->password }}
                    </td>
                    @if ($by == 'kelas')
                        <td>
                            {{ $item->kelas->name }}
                        </td>
                    @endif
                    @if ($by == 'unit')
                        <td>
                            {{ $item->unit->name }}
                        </td>
                    @endif
                    @if ($by == 'bidang')
                        <td>
                            {{ $item->bidang->name }}
                        </td>
                    @endif
                    <td>
                        <div style="text-transform: capitalize">{{ $item->status }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
