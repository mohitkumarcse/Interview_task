@extends('layout')


@section('content')
<div class="card">
    <div class="card-header">{{ __('Sign Up') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('sign-up') }}" id="sign_up_form">
            @csrf

            <!-- Employee Name -->
            <div class="mb-3">
                <label for="employee_name" class="form-label">Employee Name</label>
                <input type="text" id="employee_name" class="form-control @error('employee_name') is-invalid @enderror"
                    name="employee_name">

            </div>

            <!-- Department ID -->
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select id="department_id" class="form-control" name="department_id">
                    <option value="">Select a department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->department_id }}">
                        {{ $department->department_name }}
                    </option>
                    @endforeach
                </select>

            </div>

            <!-- Salary -->
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" id="salary" class="form-control" name="salary">

            </div>

            <!-- Designation -->
            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <input type="text" id="designation" class="form-control" name="designation"
                    value="{{ old('designation') }}">

            </div>

            <!-- Employee Type -->
            <div class="mb-3">
                <label for="employee_type" class="form-label">Employee Type</label>
                <select id="employee_type" class="form-control" name=" employee_type">
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>

            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" name="email">

            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control " name="password">

            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary" id="btn_sign_up_form">Sign Up</button>
            <a href="{{ route('sign-in') }}"><button type="button" class="btn btn-primary" style="float:right;">Sign
                    In</button></a>
        </form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#sign_up_form').on('submit', function(e) {
            e.preventDefault();

            toastr.remove();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });

                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            toastr.error(errors[field].join(', '));
                        }
                    } else {
                        toastr.error('An unexpected error occurred. Please try again.', 'Error');
                    }
                }
            });
        });
    });

</script>