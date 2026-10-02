@extends('admin.layout')
@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-lg-4">
                <div class="rounded border border-light my-2 py-4 px-3">
                    <div class="my-3 text-center text-capitalize">
                        <h4><b>@lang('text.create_campus_bank')</b></h4>
                    </div>
                    <div class="my-2">
                        <form action="{{ route('admin.banks.campus_bank.save') }}" method="post">
                            @csrf
                            <div class="my-2">
                                <label for="" class="text-secondary text-capitalize">@lang('text.word_campus')</label>
                                <select class="form-control rounded" required name="campus_id">
                                    <option value=""></option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="my-2">
                                <label for="" class="text-secondary text-capitalize">@lang('text.bank_name')</label>
                                <input type="text" class="form-control rounded" required name="bank_name">
                            </div>
                            <div class="my-2">
                                <label for="" class="text-secondary text-capitalize">@lang('text.bank_account_name')</label>
                                <input type="text" class="form-control rounded" required name="account_name">
                            </div>
                            <div class="my-2">
                                <label for="" class="text-secondary text-capitalize">@lang('text.bank_account_number')</label>
                                <input type="text" class="form-control rounded" required name="account_number">
                            </div>
                            <div class="my-3">
                                <button class="btn btn-primary form-control" type="submit">@lang('text.save_record')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
            <div class="col-md-7 col-lg-8">
                <div class="">
                    <table class="table">
                        <thead class="text-capitalize">
                            <th>@lang('text.sn')</th>
                            <th>@lang('text.word_campus')</th>
                            <th>@lang('text.bank_name')</th>
                            <th>@lang('text.account_name')</th>
                            <th>@lang('text.account_number')</th>
                            <th>@lang('text.word_action')</th>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($campus_banks as $cbank)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $cbank->campus?->name??'' }}</td>
                                    <td>{{ $cbank->bank_name??'' }}</td>
                                    <td>{{ $cbank->account_name??'' }}</td>
                                    <td>{{ $cbank->account_number??'' }}</td>
                                    <td>
                                        <a href="{{ route('admin.banks.campus_bank.edit', ['id' => $cbank->id]) }}" class="btn btn-primary btn-xs rounded text-capitalize"><span class="fa fa-pencil"></span> @lang('text.word_edit')</a>
                                        <form action="{{ route('admin.banks.campus_bank.delete', ['id' => $cbank->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger rounded text-capitalize" onclick="return confirm('Are you sure you wish to delete this item?')"><span class="fa fa-trash"></span> @lang('text.word_delete')</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
    </div> 
@endsection