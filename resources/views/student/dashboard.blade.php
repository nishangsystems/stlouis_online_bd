@extends('student.layout')
@section('section')
@php
$user = auth('student')->user();
$user = $user == null ? auth()->user() : $user;
@endphp
<!-- <div>
    <div id="user-profile-1" class="user-profile row">
        <div style="width:90%; margin-block:1.5rem; padding-block:1.5rem; font-size:2rem; font-weight:600; padding-inline:2rem;" class="shadow bg-light mx-auto rounded">
            <span class="d-block w-100 text-danger text-center">PLEASE REMEMBER TO SUBMIT YOUR FORM AT THE END OF THIS PROCESS. PRINT OUT YOUR FORM AND DEPOSIT THEM AT THE REGISTRY <span class="text-dark">NEED HELP? CALL - </span>:<span class="text-primary">672137794</span></span>
            <span class="d-block w-100 text-danger text-center"><span class="text-primary">MOMO NUMBER -</span><span class="text-secondary"> NUMÉRO MOMO</span> :6 71 98 92 92 | MOMO NAME - <span class="text-secondary">NON SUR MOMO</span> :<span class="text-dark">EMELIE BERINYUY ASHUMBENG</span> | UNDERGRADUATE APPLICATION FEE - :<span class="text-primary">5,000 XAF</span> BACHELOR APPLICATION FEE - :<span class="text-primary">10,000 XAF </span> MASTERS APPLICATION FEE - :<span class="text-primary">20,000 XAF </span></span>
        </div>
        {{-- @if()
        @endif --}}
        <div class="my-5 py-3 mx-auto text-center alert-info shadow" style="width:90%; font-size:larger; font-weight:600">
            lorem cjkjewhr iuhyiuehgwtr wk erijewhtjh sgfe t g w i i tit g jhewyu trgtuegt ht thgj
        </div>
    </div>
</div> -->
@endsection