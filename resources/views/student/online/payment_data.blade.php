@extends('student.layout')
@section('section')
    <div>
        @if (request('appl') == null)
            <table class="table">
                <thead class="text-uppercase border-bottom">
                    <th class="border-left border-right">{{ __('text.sn') }}</th>
                    <th class="border-left border-right">{{ __('text.word_applicant') }}</th>
                    <th class="border-left border-right">{{ __('text.word_campus') }}</th>
                    <th class="border-left border-right">{{ __('text.word_degree') }}</th>
                    <th class="border-left border-right"></th>
                </thead>
                <tbody>
                    @php($k = 1)
                    @foreach ($payments as $key => $payment)
                        <tr class="border-bottom">
                            <td class="border-left border-right">{{ $k++ }}</td>
                            <td class="border-left border-right">{{ $payment->name??'' }}</td>
                            <td class="border-left border-right">{{ $payment->campus->name??'' }}</td>
                            <td class="border-left border-right">{{ $payment->degree->deg_name??'' }}</td>
                            <td class="border-left border-right">
                                <a href="{{ Request::url() }}?appl={{ $payment->id }}"  class="btn btn-sm btn-primary">{{ __('text.word_view') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="row my-4 py-4 rounded-bottom border-top bg-light text-capitalize">
                <div class="col-sm-12 col-md-7 col-lg-7" style="font-size: 2rem;">
                    <span class="d-block w-100 my-3">momo number : <span class="text-primary">{{ $appl->momo_number }}</span></span>
                    <span class="d-block w-100 my-3">momo transaction id : <span class="text-primary">{{ $appl->momo_transaction_id }}</span></span>
                    <span class="d-block w-100 my-3">amount payed : <span class="text-primary">{{ $appl->amount }} CFA</span></span>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-5">
                    <img class="img img-rounded mx-auto" width="200px" height="200px" src="{{ $appl->momo_screenshot }}">
                </div>
            </div>
        @endif
    </div>
@endsection