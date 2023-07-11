@extends('admin.layout')
@section('section')
    <div class="py-3">
        <div class="py-2">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-stripped" id="hidden-table-info">
                <thead>
                    <tr class="text-capitalize">
                        <th>#</th>
                        <th>{{__('text.word_name')}}</th>
                        <th>{{__('text.word_email')}}</th>
                        <th>{{__('text.word_phone')}}</th> 
                        <th>{{__('text.word_degree')}}</th> 
                        <th>{{__('text.program_first_choice')}}</th>
                        <th>{{__('text.program_second_choice')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="table_body">
                    @php($k = 1)
                    @foreach ($applications as $appl)
                        <tr class="border-bottom">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $appl->name }}</td>
                            <td class="border-left border-right">{{ $appl->email }}</td>
                            <td class="border-left border-right">{{ $appl->phone }}</td>
                            <td class="border-left border-right">{{ collect(json_decode($_this->api_service->degrees())->data)->where('id', $appl->degree_id)->first()->deg_name }}</td>
                            <td class="border-left border-right">{{ collect(json_decode($_this->api_service->programs())->data)->where('id', $appl->program_first_choice)->first()->name }}</td>
                            <td class="border-left border-right">{{ collect(json_decode($_this->api_service->programs())->data)->where('id', $appl->program_second_choice)->first()->name }}</td>
                            <td class="border-left border-right">
                                @if($appl->admitted != true)
                                    <a href="{{ route('admin.admission.admit', $appl->id) }}" class="btn btn-sm btn-primary rounded">{{ __('text.word_admit') }}</a>
                                    <a href="{{ route('admin.admission.show', $appl->id) }}" class="btn btn-sm btn-success rounded">{{ __('text.word_details') }}</a>
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