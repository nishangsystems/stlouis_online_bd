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
                @foreach ($applications as $appl)
                    <tr class="border-bottom">
                        <td class="border-left border-right">{{ $k++ }}</td>
                        <td class="border-left border-right">{{ $appl->year->name }}</td>
                        <td class="border-left border-right">{{ $appl->first_name.' '.$appl->surname }}</td>
                        <td class="border-left border-right">{{ $appl->programFirstChoice->name.' / '.$appl->programSecondChoice->name }}</td>
                        <td class="border-left border-right d-flex flex-wrap">
                            <form method="post" action="{{ route('student.application.form.download', $appl->id) }}">@csrf
                                <input type="submit" class="btn btn-xs btn-primary mx-2" value="{{ __('text.word_download') }}">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection