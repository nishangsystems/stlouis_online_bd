@extends('admin.layout')
@section('section')
    <div class="py-3">
        <div class="py-3">
            <table class="table table-light">
                <thead class="text-uppercase border-bottom">
                    <th class="border-left border-right">{{ __('text.sn') }}</th>
                    <th class="border-left border-right">{{ __('text.word_degree') }}</th>
                    <th class="border-left border-right"></th>
                </thead>
                <tbody>
                    @php($k = 1)
                    @foreach ($degrees as $degree)
                        <tr class="border-bottom text-capitalize {{ request('degree_id') == $degree->id ? 'alert-success' : '' }}">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $degree->deg_name }}</td>
                            <td class="border-left border-right"><a class=" btn btn-sm btn-primary" href="{{ route('admin.admission.degree.certificates', $degree->id) }}">{{ __('text.word_configure') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(request('degree_id') != null)
            <div class="py-3">
                <form method="POST">
                    @csrf
                    <table>
                        <thead class="text-uppercase bg-light py-1 border-bottom">
                            <th class="border-left border-right"></th>
                            <th class="border-left border-right">{{ __('text.sn') }}</th>
                            <th class="border-left border-right">{{ __('text.word_certificate') }}</th>
                        </thead>
                        <tbody>
                        @php($k = 1)
                            @foreach ($certificates??[] as $cert)
                                <tr class="border-bottom">
                                    <td class="border-left border-right"><input type="checkbox" value="{{ $cert->id }}" name="certificates[]" {{ (isset($degree_certificates) and in_array($cert->id, $degree_certificates)) ? 'checked' : '' }}></td>
                                    <td class="border-left border-right">{{ $k++ }}</td>
                                    <td class="border-left border-right">{{ $cert->certi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end py-2"><button class="btn btn-xs btn-primary" type="submit">{{ __('text.word_update') }}</button></div>
                </form>
            </div>
        @endif
    </div>
@endsection