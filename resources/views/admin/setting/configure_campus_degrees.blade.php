@extends('admin.layout')
@section('section')
    <div class="py-3">
        <div class="py-3">
            <table class="table table-light">
                <thead class="text-uppercase border-bottom">
                    <th class="border-left border-right">{{ __('text.sn') }}</th>
                    <th class="border-left border-right">{{ __('text.word_campus') }}</th>
                    <th class="border-left border-right"></th>
                </thead>
                <tbody>
                    @php($k = 1)
                    @foreach ($campuses as $campus)
                        <tr class="border-bottom text-capitalize {{ request('cid') == $campus->id ? 'alert-success' : '' }}">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $campus->name }}</td>
                            <td class="border-left border-right"><a class=" btn btn-sm btn-primary" href="{{ route('admin.admission.campus.degrees', $campus->id) }}">{{ __('text.word_configure') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(request('cid') != null)
            <div class="py-3">
                <form method="POST">
                    @csrf
                    <table>
                        <thead class="text-uppercase bg-light py-1 border-bottom">
                            <th class="border-left border-right"></th>
                            <th class="border-left border-right">{{ __('text.sn') }}</th>
                            <th class="border-left border-right">{{ __('text.word_degree') }}</th>
                            {{-- <th class="border-left border-right"></th> --}}
                        </thead>
                        <tbody>
                        @php($k = 1)
                            @foreach ($degrees??[] as $deg)
                                <tr class="border-bottom">
                                    <td class="border-left border-right"><input type="checkbox" value="{{ $deg->id }}" name="campus_degrees[]" {{ (isset($campus_degrees) and in_array($deg->id, $campus_degrees)) ? 'checked' : '' }}></td>
                                    <td class="border-left border-right">{{ $k++ }}</td>
                                    <td class="border-left border-right">{{ $deg->deg_name }}</td>
                                    {{-- <td class="border-left border-right"><a href="" class="btn btn-xs btn-primary">{{ __('text.word_edit') }}</a></td> --}}
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