@extends('admin.layout')
@section('section')
    <div class="py-3 row">
        <div class="col-lg-4 py-3">
            <table class="table">
                <thead class="text-capitalize">
                    <th class="d-lg-none d-xxl-inline">@lang('text.sn')</th>
                    <th>@lang('text.word_campus')</th>
                    <th></th>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($campuses as $campus)
                        <tr>
                            <td class="d-lg-none d-xxl-inline">{{ $counter++ }}</td>
                            <td>{{ $campus->name }}</td>
                            <td><a class="btn btn-primary btn-sm rounded" href="{{ route('admin.program_provisions.config', $campus->id) }}">@lang('text.word_configure')</a></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <form class="col-lg-8 py-3 card rounded-0 shadow" method="POST">
            @csrf
            <div class="card-body" style="max-height: 85vh; overflow-y: scroll;">
                <table class="table-light">
                    <thead class="text-capitalize border-top border-bottom sticky-top bg-light">
                        <th>@lang('text.sn')</th>
                        <th>@lang('text.word_program')</th>
                        @foreach ($status_set as $stats)
                            <th><input class="input" checker="{{ $stats['name'] }}" onchange="checkAll(this)" type="checkbox">{{ $stats['name'] }}</th>
                        @endforeach
                    </thead>
                    <tbody>
                        @isset($programs)
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($programs as $program)
                                <tr class="border-bottom">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $program->name }}</td>
                                    @foreach ($status_set as $set)
                                        <td><input type="checkbox" class="checkbox input" mark="{{ $set['name'] }}" name="program_status[{{ $program->id }}][]" value="{{ $set['name'] }}" {{ $program->_stats != null && in_array($set['name'], $program->_stats) ? 'checked' : '' }}></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end py-2">
                <button class="btn btn-sm rounded btn-primary" type="submit">@lang('text.word_save')</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        let checkAll = function(element){
            let checker = $(element).attr('checker');
            $(`input[mark="${checker}"]`).each((i, el)=>{
                (element).checked == true ?
                $(el).prop('checked', true) : 
                $(el).prop('checked', false) ;
            })
        }
    </script>
@endsection