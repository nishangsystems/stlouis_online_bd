@extends('admin.layout')
@section('section')
@php($year = request('year') ?? \App\Helpers\Helpers::instance()->getCurrentAccademicYear())
<div class="py-3">
    <form method="get" >
        <div class="form-group-merged d-flex border-secondary">
            <label class="fw-bold text-capitalize col-sm-4 col-md-4">{{__('text.academic_year')}}</label>
            <div class="col-sm-8 col-md-8">
                <select name="" id="year" class="form-control" onchange="event.preventDefault(); redirect(event)">
                @foreach(\App\Models\Batch::all() as $y)
                    <option value="{{$y->id}}" {{$y->id == $year ? 'selected' : ''}}>{{$y->name}}</option>
                @endforeach
                </select>
            </div>
        </div>
    </form>
    <table class="table">
        <div class="h4 text-uppercase text-center text-dark">{{$title .' FOR '.\App\Models\Batch::find($year)->name}}</div>
        <thead class="text-capitalize">
            <!-- <th>###</th> -->
            <th>###</th>
            <th>{{__('text.word_name')}}</th>
            <th>{{__('text.word_matricule')}}</th>
            <th>{{__('text.word_class')}}</th>
        </thead>
        <tbody>
            @php($k = 1)
            @foreach(\App\Models\Stock::find(request('id'))->studentStock(auth()->user()->campus_id ?? null)->where(['year_id'=>$year])->get() as $item)
            <tr class="border-bottom border-secondary">
                <td class="border">{{$k++}}</td>
                <td class="border">{{$item->student->name}}</td>
                <td class="border">{{$item->student->matric}}</td>
                <td class="border">{{$item->student->_class($year) != null ? $item->student->_class($year)->name() : '----'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex my-3 justify-content-end pr-3">
        <a href="{{Request::url()}}/print" class="btn btn-sm btn-primary">{{__('text.word_print')}}</a>
    </div>
</div>
@endsection
@section('script')
<script>
    function redirect(event) {
        val = event.target.value;
        url = "{{route('admin.stock.report', [request('id'), '__VAL__'])}}";
        url = url.replace('__VAL__', val);
        window.location = url;
    }
</script>
@endsection