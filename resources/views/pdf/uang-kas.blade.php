@extends('layouts.pdf')
@section('title', 'Laporan Uang Kas')

@section('table')
    <table>
        <thead>
            <tr>
                <td style="text-align: center">No</td>
                <td style="text-align: center">Bulan</td>
                <td>Pemasukan</td>
                <td>Pengeluaran</td>
                <td style="text-align: center">Saldo</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($uangKas as $item)
                <tr>
                    <td rowspan="2" style="text-align: center">{{ $loop->iteration }}</td>
                    <td rowspan="2" style="text-align: center; text-transform: capitalize">{{ $item->bulan }}</td>
                    <td>Rp. {{ number_format($item->pemasukan, 0, ',','.') }}</td>
                    <td></td>
                    <td rowspan="2" style="text-align: center">Rp. {{ number_format($item->saldo, 0, ',','.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Rp. {{ number_format($item->pengeluaran, 0, ',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
