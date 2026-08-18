@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <form method="get">
            <div class="row">
                <div class="col-md-9">
                    <select name="year_id" id="" class="form-control rounded">
                        <option value=""></option>
                        @foreach ($years as $yr)
                            <option value="{{ $yr->id }}" {{ request('year_id') == $yr->id ? 'selected' : '' }}>{{ $yr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-xs btn-primary rounded">@lang('text.word_next')</button>
                </div>
            </div>
        </form>
    </div>
@endsection