@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <div style=""></div>
        <form method="post">
            @csrf
            <div class="row">
                <div class="col-md-6 col-xl-5" style="margin-block: 0.7rem;">
                    <select name="program_id" id="" class="form-control" required>
                        <option value="">@lang('text.select_program')</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-info text-capitalize">@lang('text.word_program') <span class="text-danger">*</span> </span>
                </div>
                <div class="col-md-6 col-xl-5" style="margin-block: 0.7rem;">
                    <input type="file" name="file" required class="form-control">
                    <span class="text-info text-capitalize">@lang('text.word_file') <span class="text-danger text-capitalize"></span>@lang('text.file_format_csv') *</span>
                </div>
                <div class="col-md-12 col-xl-2" style="margin-block: 0.7rem;">
                    <input type="submit" value="{{ __('text.word_import') }}" class="form-control btn btn-primary btn-md rounded">
                </div>
            </div>
        </form>
    </div>
@endsection