@extends('admin.layout')
@section('section')
    @php
        $campuses = collect(json_decode($_this->api_service->campuses())->data);
        $degrees = collect(json_decode($_this->api_service->degrees())->data);
        $programs = collect(json_decode($_this->api_service->programs())->data);
        $years = \App\Models\Batch::all();
    @endphp
    <div class="py-3">
        <div class="py-2">
            <div class="container-fluid">
                <form method="get">
                    <div class="row">
                        <div class="col-9">
                            <select name="year_id" id="" class="form-control">
                                <option class=""></option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-secondary"><i>@lang('text.word_year')</i></small>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <span><button type="submit" class="btn btn-xs btn-primary rounded">@lang('text.word_filter')</button></span>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-light table-stripped" id="hidden-table-info">
                <thead>
                    <tr class="text-capitalize border-bottom border-dark">
                        <th class="border-left border-right" rowspan="2">#</th>
                        <th class="border-left border-right" rowspan="2">{{__('text.word_name')}}</th>
                        <th class="border-left border-right" rowspan="2">{{__('text.word_email')}}</th>
                        <th class="border-left border-right" rowspan="2">{{__('text.word_phone')}}</th> 
                        <th class="border-left border-right" rowspan="2">{{__('text.word_campus')}}</th> 
                        <th class="border-left border-right" rowspan="2">{{__('text.word_degree')}}</th> 
                        <th class="border-left border-right" colspan="2">{{__('text.word_programs')}}</th> 
                        <th class="border-left border-right" rowspan="2"></th>
                    </tr>
                    <tr class="text-capitalize border-bottom border-dark">
                        <th class="border-left border-right">{{__('text.word_first')}}</th>
                        <th class="border-left border-right">{{__('text.word_second')}}</th> 
                    </tr>
                </thead>
                <tbody id="table_body">
                    @php($k = 1)
                    @foreach ($applications as $appl)
                        <tr class="border-bottom">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $appl?->name == null ? (\App\Models\Students::find($appl->student_id)?->name??'') : ($appl->name??'') }}</td>
                            <td class="border-left border-right">{{ $appl?->email == null ? (\App\Models\Students::find($appl->student_id)?->email??'') : ($appl->email??'') }}</td>
                            <td class="border-left border-right">{{ $appl?->phone == null ? (\App\Models\Students::find($appl->student_id)?->phone??'') : ($appl->phone??'') }}</td>
                            <td class="border-left border-right">{{ $campuses?->where('id', $appl?->campus_id??0)->first()->name??null }}</td>
                            <td class="border-left border-right">{{ $degrees?->where('id', $appl?->degree_id??0)->first()->deg_name??null }}</td>
                            <td class="border-left border-right">{{ $programs?->where('id', $appl?->program_first_choice??0)->first()->name??null }}</td>
                            <td class="border-left border-right">{{ $programs?->where('id', $appl?->program_second_choice??0)->first()->name??null }}</td>
                            <td class="border-left border-right">
                                @if(isset($action))
                                    <a href="{{ Request::url().'/'.$appl?->id??0 }}" class="btn btn-xs btn-primary mt-1">{{ $action }}</a>
                                @endif
                                @if(isset($download))
                                   <a href="{{ Request::url() }}/{{  $appl?->id??0 }}?_atn=_dld" class="btn btn-xs btn-primary mt-1">{{ $download }}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">

            </div>
        </div>
    </div>
@endsection