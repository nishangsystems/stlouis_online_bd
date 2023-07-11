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
                        <tr class="border-bottom text-capitalize">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $campus->name }}</td>
                            <td class="border-left border-right"><a class=" btn btn-sm btn-primary" href="{{ route('admin.admission.campus.degrees', $campus->id) }}">{{ __('text.word_configure') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-3">
            @if (request('cid') != null)
                <div class="d-flex justify-content-end py-2"><a class="btn btn-sm btn-primary" href="{{ route('admin.admission.campus.degrees', $campus->id) }}?act=new">{{ __('text.word_add') }}</a></div> 
            @endif
            <form method="POST">
                <table>
                    <thead class="text-uppercase bg-light py-1 border-bottom">
                        <th class="border-left border-right">{{ __('text.sn') }}</th>
                        <th class="border-left border-right">{{ __('text.word_degree') }}</th>
                        <th class="border-left border-right"></th>
                    </thead>
                    <tbody>
                    @php($k = 1)
                        @foreach ($campus_degrees??[] as $deg)
                            <tr class="border-bottom">
                                <td class="border-left border-right">{{ $k++ }}</td>
                                <td class="border-left border-right">{{ $deg->deg_name }}</td>
                                <td class="border-left border-right"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection