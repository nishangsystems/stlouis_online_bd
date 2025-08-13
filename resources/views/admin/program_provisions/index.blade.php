@extends('admin.layout')
@section('section')
    <div class="py-3">
        <div class="py-2 d-flex justify-content-end my-3">
            <a href="{{ route('admin.program_provisions.config') }}" class="btn btn-primary rounded">@lang('text.word_configure')</a>
        </div>
        <div id="accordion" class="accordion-style1 panel-group">
            @foreach ($data as $cname => $cpstats)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#{{ str_replace(' ', '_', $cname) }}_program_config" aria-expanded="false">
                                <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                &nbsp; {{ $cname }}
                            </a>
                        </h4>
                    </div>
                    <div class="panel-collapse collapse" id="{{ str_replace(' ', '_', $cname) }}_program_config" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body">
                            <table class="table-stripped table-light">
                                <thead class="text-capitalize border-bottom">
                                    <th>###</th>
                                    <th>@lang('text.word_program')</th>
                                    @foreach($status_set as $key => $st)
                                        <th>{{ $st['name'] }}</th>
                                    @endforeach
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($cpstats as $stat)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $stat['program'] }}</td>
                                            @foreach ($status_set as $st)
                                                <td>
                                                    @if(in_array($st['name'], $stat['status']))
                                                        <span class="fa fa-check fa-2x text-success"></span>
                                                    @else
                                                        <span class="fa fa-close fa-2x text-danger"></span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection