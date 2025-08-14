@extends('admin.layout')
@section('section')
    <div class="py-3">
        @if(request('cid') == null)
            <table class="table">
                <thead class="bg-light text-uppercase border-bottom">
                    <th class="border-left border-right">{{ __('text.sn') }}</th>
                    <th class="border-left border-right">{{ __('text.word_certificate') }}</th>
                    <th class="border-left border-right"></th>
                </thead>
                <tbody>
                    @php($k = 1)
                    @if(isset($certs) and is_array($certs))
                        @foreach ($certs as $crt)
                            <tr class="border-bottom">
                                <td class="border-left border-right">{{ $k++ }}</td>
                                <td class="border-left border-right">{{ $crt->certi }}</td>
                                <td class="border-left border-right"><a class="btn btn-sm btn-primary" href="{{ route('admin.admission.programs.config', $crt->id) }}">{{ __('text.word_programs') }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @else
            <form method="post">
                @csrf
                @if (isset($cert) and $cert != null)
                    <div class="text-center text-uppercase border-top border-bottom border-2 border-secondary" style="font-size: 2.2rem; font-weight: 700;"><span class="text-secondary mr-5">{{ __('text.entry_qualification') }}</span> <span class="text-danger">{{ $cert->certi }}</span></div>
                    @if(isset($programs) and $programs != null)
                        <table class="border">
                            <thead class="text-uppercase border-bottom">
                                <th class="border-left border-right"></th>
                                <th class="border-left border-right">{{ __('text.sn') }}</th>
                                <th class="border-left border-right">{{ __('text.word_department') }}</th>
                                <th class="border-left border-right">{{ __('text.word_program') }}</th>
                            </thead>
                            <tbody>
                                @php($k = 1)
                                @foreach ($programs as $prog)
                                    <tr class="border-bottom text-capitalize">
                                        <td class="border-left border-right"><input type="checkbox" name="programs[]" value="{{ $prog->id }}" {{ in_array($prog->id, $cert_programs) ? 'checked' : '' }}></td>
                                        <td class="border-left border-right">{{ $k++ }}</td>
                                        <td class="border-left border-right">{{ $prog->parent }}</td>
                                        <td class="border-left border-right">{{ $prog->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <div class="text-center text-uppercase border-top border-bottom border-2 d-flex justify-content-end border-secondary py-3" style="">
                        <input type="submit" class="btn btn-primary btn-sm px-5 py-1" value="{{ __('text.word_save') }}">
                    </div>
                @endif
            </form>
        @endif
    </div>
@endsection