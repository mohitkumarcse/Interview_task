@extends('layout')

@section('content')
<div class="container">
    <h1>Employee Details</h1>

    <div class="card">
        <div class="card-header">
            Employee Information
            <button type="button" class="btn btn-warning logout-btn float-lg-end" style="
            float: right;">Logout</button>
        </div>
        <div class="card-body">
            <p><strong>Employee Name:</strong> {{ $employee->employee_name }}</p>
            <p><strong>Employee Email:</strong> {{ $employee->email }}</p>
            <p><strong>Designation:</strong> {{ $employee->designation }}</p>
            <p><strong>Salary:</strong> {{ $employee->salary }}</p>
            <p><strong>Employee Type:</strong> {{ $employee->employee_type }}</p>
            <p><strong>Department:</strong> {{ $employee->department_name }}</p>
        </div>
    </div>


</div>
@endsection
<script src=" https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.logout-btn',function(e){
        $.ajax({
            url: `logout`,
            type: 'GET',
            success: function (response) {
                toastr.success(response.message);
                setTimeout(function() {
                    window.location.href = response.redirect_url;
                }, 3000);
            },
            error: function (xhr) {
                alert('Failed to fetch employee details.');
            }
        });
    });
</script>