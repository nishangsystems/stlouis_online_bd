@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <form method="get">
            <div class="row">
                <div class="col-md-9">
                    <select name="year_id" id="" class="form-control rounded">
                        <option value=""></option>
                        @foreach ($years as $yr)
                            <option value="{{ $yr->id }}" {{ request('year_id') == $yr->id ? 'selected' : '' }}>{{ $yr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-xs btn-primary rounded">@lang('text.word_next')</button>
                </div>
            </div>
        </form>
        <table class="table">
            <thead class="text-capitalize">
                <th>@lang('text.sn')</th>
                <th>@lang('text.info_channel')</th>
                <th>@lang('text.word_count')</th>
                <th>@lang('text.word_action')</th>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach ($records as $rec)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $rec->referer}}</td>
                        <td>{{ $rec->count}}</td>
                        <td>
                            <a href="{{ route('admin.reports.application.referal_report.details') }}?item={{ $rec->referer }}&year_id={{ request('year_id') }}" class="btn btn-xs btn-primary rounded">@lang('text.word_details')</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection