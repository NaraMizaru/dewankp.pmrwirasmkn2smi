@extends('layouts.pdf')
@section('title', 'Vote Logs ' . $pemilu->name)

@section('table')
    <table>
        <thead>
            <tr>
                <td style="text-align: center">No</td>
                <td>Nama Pemilih</td>
                <td>Kandidat Terpilih</td>
                <td>Waktu Voting</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($votings as $item)
                <tr>
                  <td style="text-align: center">{{ $loop->iteration }}</td>
                  <td>{{ $item->user->fullname }}</td>
                  <td>{{ $item->kandidat->description }} : {{ $item->kandidat->name }}</td>
                  <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
