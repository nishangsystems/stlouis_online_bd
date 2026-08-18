@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <table class="table">
            <thead class="text-capitalize">
                <th>@lang('text.sn')</th>
                <th>@lang('text.word_applicant')</th>
                <th>@lang('text.word_program')</th>
                <th>@lang('text.word_referer')</th>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach ($records as $rec)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $rec->name??'' }}</td>
                        <td>{{ $rec->program??'' }}</td>
                        <td>{{ $rec->referer??'' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection