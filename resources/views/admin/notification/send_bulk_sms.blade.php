@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <div class="mpy-4 my-1 d-flex justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-6 border border-secondary rounded px-2 py-5 my-2 mx-auto">
                <form method="post">
                    @csrf
                    <div class="my-2">
                        <select name="option" class="form-control rounded" required id="">
                            <option value=""></option>
                            @foreach ($options as $opt => $val)
                                <option value="{{ $opt }}">{{ $val }}</option>
                            @endforeach
                        </select>
                        <label for="" class="text-secondary text-capitalize">@lang('text.word_option')</label>
                    </div>
                    <div class="my-2">
                        <textarea name="message" class="form-control rounded" required id="" rows="4"></textarea>
                        <label for="" class="text-secondary text-capitalize">@lang('text.word_message')</label>
                    </div>
                    <div class="mt-4 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm form-control rounded">@lang('text.word_send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection