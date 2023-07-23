@extends('admin.printable2')
@section('section')
    <div class="py-2" style="line-height: 2.3rem; font-size:larger;">
        <span class="d-block py-2 text-capitalize">{!! __('text.admission_letter_phrase1', ['name'=>$name]) !!}</span>
        <span class="d-block py-2">{!! __('text.admission_letter_phrase2', ['campus'=>$campus->name, 'program'=>$program->name, 'matric'=>$matric]) !!}</span>
        <ul style="list-style-type:disc;margin-block:2rem;">
            <li><span class="d-block py-2">{!! __('text.admission_letter_phrase3') !!}</span></li>
            <li><span class="d-block py-2">{!! __('text.admission_letter_phrase4', ['date1'=>$fee1_dateline->format('dS \of F Y'), 'date2'=>$fee2_dateline->format('dS \of F Y')]) !!}</span></li>
            <li><span class="d-block py-2">{!! __('text.admission_letter_phrase5') !!}</span></li>
        </ul>
        <span class="d-block py-3">{!! __('text.admission_letter_phrase6', ['email'=>$help_email]) !!}</span>
        <span class="d-block py-3">{!! __('text.admission_letter_phrase7', ['matric'=>$matric]) !!}</span>
        <div class="d-flex justify-content-around py-3 text-capitalize">
            <span class="text-center d-block py-4" style="font-weight: 700;">{!! __('text.the_director') !!}<br>{!! $director_name !!}</span>
            <span class="text-center d-block py-4" style="font-weight: 700;">{!! __('text.the_dean_of_studies') !!}<br>{!! $dean_name !!}</span>
        </div>
    </div>
@endsection