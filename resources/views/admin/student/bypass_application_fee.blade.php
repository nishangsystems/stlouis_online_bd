@extends('admin.layout')
@section('section')
    <div class="py-3">
        @isset($student)
            <div class="container-fluid">
                <form class="py-3 bg-light shadow px-4" method="POST">
                    @csrf
                    <div class="text-info text-capitalize">@lang('text.word_reason')</div>
                    <textarea class="form-control rounded" rows="4" required name="reason"></textarea>
                    <div class="my-3 d-flex justify-content-end">
                        <button class="btn btn-xs btn-primary rounded" type="submit">bypass</button>
                    </div>
                </form>
            </div>
            <hr class="my-4">
        @endisset
        <div class="container-fluid">
            <div class="py-2 my-3 alert-success text-center border-top border-bottom px-5">
                <h5 class="my-2">Search student by name, email, or phone number</h5>
                <input type="text" class="form-control rounded my-2" oninput="search_student(this)">
            </div>
            <table class="table">
                <thead class="text-capitalize">
                    <th>#</th>
                    <th>@lang('text.word_name')</th>
                    <th>@lang('text.word_email')</th>
                    <th>@lang('text.word_phone')</th>
                    <th></th>
                </thead>
                <tbody id="students_table_body">
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let search_student = (element)=>{
            let _student = $(element).val();
            let _url = "{{ route('search_forms') }}";
            $.ajax({
                url: _url, method: 'GET', data: {key: _student},
                success: function(data){
                    console.log(data);
                    let html = ``;
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        html += `<tr>
                                <td>${index+1}</td>
                                <td>${element.name}</td>
                                <td>${element.email}</td>
                                <td>${element.phone}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary rounded" href="{{ route('admin.application.bypass', '__STID__') }}">bypass</a>
                                </td>
                            </tr>`.replace('__STID__', element.id);
                    }
                    $('#students_table_body').html(html);
                }
            })
        };
    </script>
@endsection