@extends('layouts.pdf')
@section('title', 'Statistik ' . $pemilu->name)

@section('table')
    <table>
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th>Nama Kandidat</th>
                <th>Total Pemilih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kandidat as $item)
                <tr>
                  <td style="text-align: center">{{ $loop->iteration }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->voting()->count() }}</td>
                </tr>
            @endforeach
            <tr>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td colspan="2">Jumlah Pemilih</td>
              <td>{{ $voted }}</td>
            </tr>
        </tbody>
    </table>
@endsection
