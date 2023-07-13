@extends('student.layout')
@section('section')
    <div class="py-3">
        <table class="table">
            <thead><tr class="text-capitalize">
                <th>{{ __('text.sn') }}</th>
                <th>{{ __('text.word_year') }}</th>
                <th>{{ __('text.word_applicant') }}</th>
                <th>{{ __('text.word_program') }}</th>
                <th></th>
            </tr></thead>
            <tbody>
                @php($k = 1)
                @php($programs = collect(json_decode($_this->api_service->programs())->data))
                @foreach ($applications->whereNotNull('degree_id') as $appl)
                    <tr class="border-bottom">
                        <td class="border-left border-right">{{ $k++ }}</td>
                        <td class="border-left border-right">{{ $appl->year->name }}</td>
                        <td class="border-left border-right">{{ $appl->name }}</td>
                        <td class="border-left border-right">{{ $programs->where('id', $appl->program_first_choice)->first()->name .' / '.$programs->where('id', $appl->program_second_choice)->first()->name??'' }}</td>
                        <td class="border-left border-right d-flex flex-wrap">
                            <form method="post" action="{{ route('student.application.form.download', $appl->id) }}" target="new">@csrf
                                <input type="submit" class="btn btn-xs btn-primary mx-2" value="{{ __('text.word_download') }}">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection