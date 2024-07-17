@extends('layouts.pdf')
@section('title', 'Daftar List Program Kerja')

@section('table')
    @if (Request::query('type') == 'all')
        @foreach ($unit as $units)
            <table style="margin-top: 2rem">
                <thead>
                    <tr>
                        <th colspan="4">Nama Unit : {{ $units->name }}</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; width: 5%">
                            No
                        </th>
                        <th style="width: 50%">Nama Proker</th>
                        <th style="text-align: center; width: 25%">Tanggal Selesai</th>
                        <th style="text-align: center; width: 20%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($units->id == 1)
                        @foreach ($prokerUnit1 as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td style="text-align: center">
                                    {{ $item->tanggal ?? '-' }}
                                </td>
                                <td style="text-align: center">
                                    <div style="text-transform: capitalize">{{ $item->status }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if ($units->id == 2)
                        @foreach ($prokerUnit2 as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td style="text-align: center">
                                    {{ $item->tanggal ?? '-' }}
                                </td>
                                <td style="text-align: center">
                                    <div style="text-transform: capitalize">{{ $item->status }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if ($units->id == 3)
                        @foreach ($prokerUnit3 as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td style="text-align: center">
                                    {{ $item->tanggal ?? '-' }}
                                </td>
                                <td style="text-align: center">
                                    <div style="text-transform: capitalize">{{ $item->status }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if ($units->id == 4)
                        @foreach ($prokerUnit4 as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td style="text-align: center">
                                    {{ $item->tanggal ?? '-' }}
                                </td>
                                <td style="text-align: center">
                                    <div style="text-transform: capitalize">{{ $item->status }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @endforeach
    @else
        <table>
            <thead>
                <tr>
                    <th colspan="4">Nama Unit : {{ $unit->name }}</th>
                </tr>
                <tr>
                    <th style="text-align: center; width: 5%">
                        No
                    </th>
                    <th style="width: 50%">Nama Proker</th>
                    <th style="text-align: center; width: 25%">Tanggal Selesai</th>
                    <th style="text-align: center; width: 20%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proker as $item)
                    <tr>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item->name }}
                        </td>
                        <td style="text-align: center">
                            {{ $item->tanggal ?? '-' }}
                        </td>
                        <td style="text-align: center">
                            <div style="text-transform: capitalize">{{ $item->status }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
