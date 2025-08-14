@extends('student.layout')
@section('section')
@php
$user = auth('student')->user();
$user = $user == null ? auth()->user() : $user;
@endphp
    <!-- <div>
        <div id="user-profile-1" class="user-profile row">
            <div style="width:90%; margin-block:1.5rem; padding-block:1.5rem; font-size:2rem; font-weight:600; padding-inline:2rem;" class="shadow bg-light mx-auto rounded">
                <span class="d-block w-100 text-danger text-center">PLEASE REMEMBER TO SUBMIT YOUR FORM AT THE END OF THIS PROCESS. PRINT OUT YOUR FORM AND DEPOSIT THEM AT THE REGISTRY <span class="text-dark">NEED HELP? CALL - </span>:<span class="text-primary">672137794</span></span>
                <span class="d-block w-100 text-danger text-center"><span class="text-primary">MOMO NUMBER -</span><span class="text-secondary"> NUMÉRO MOMO</span> :6 71 98 92 92 | MOMO NAME - <span class="text-secondary">NON SUR MOMO</span> :<span class="text-dark">EMELIE BERINYUY ASHUMBENG</span> | UNDERGRADUATE APPLICATION FEE - :<span class="text-primary">5,000 XAF</span> BACHELOR APPLICATION FEE - :<span class="text-primary">10,000 XAF </span> MASTERS APPLICATION FEE - :<span class="text-primary">20,000 XAF </span></span>
            </div>
            {{-- @if()
            @endif --}}
            <div class="my-5 py-3 mx-auto text-center alert-info shadow" style="width:90%; font-size:larger; font-weight:600">
                lorem cjkjewhr iuhyiuehgwtr wk erijewhtjh sgfe t g w i i tit g jhewyu trgtuegt ht thgj
            </div>
        </div>
    </div> -->
    <div class="py-3">
        <div class="card">
            <div class="my-3 row card-body">
                @foreach ($status_set as $sset)
                    <div class="col-md-6 col-lg-4">
                        <div class="m-2 rounded shadow py-2 px-3">
                            <h4 class="title text-center">{{ $sset['name'] }}</h4>
                            <hr class="my-2 border-top">
                            <p>{{ $sset['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="accordion" class="accordion-style1 panel-group">
            @foreach ($data as $cname => $cpstats)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#{{ $cname }}_program_config" aria-expanded="false">
                                <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                &nbsp; {{ $cname }}
                            </a>
                        </h4>
                    </div>
                    <div class="panel-collapse collapse" id="{{ $cname }}_program_config" aria-expanded="false" style="height: 0px;">
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