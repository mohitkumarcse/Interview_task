@extends('layout')

@section('content')



<div class="card">
    <div class="card-header">
        Employee List
        <button type="button" class="btn btn-warning logout-btn float-lg-end" style="
        float: right;">Logout</button>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <th>Salary</th>
                    <th>Designation</th>
                    <th>Employee Type</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @php
                $i=1;
                @endphp


                @if ( $employees->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">No Data Found</td>
                </tr>
                @else

                @foreach ($employees as $employee)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->salary }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td>{{ $employee->employee_type }}</td>
                    <td>{{ $employee->department_name }}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm edit-btn" data-toggle="modal"
                            data-target="#updateModal" data-id="{{ $employee->employee_id }}">
                            Edit
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm"
                            data-url="{{ route('destroy', $employee->employee_id) }}">Delete</button>
                        <button type="submit" class="btn btn-success btn-sm"
                            data-url="{{ route('view', $employee->employee_id) }}">View</button>


                    </td>
                </tr>


                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Employee Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="updateForm" action="#" method="POST" id="updateForm">
                                    @csrf

                                    <input type="hidden" id="emp_id" name="emp_id">
                                    <div class=" form-group">
                                        <label for="employee_name">Employee Name</label>
                                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="salary">Salary</label>
                                        <input type="number" class="form-control" id="salary" name="salary" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" id="designation" name="designation"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee_type">Employee Type</label>
                                        <input type="text" class="form-control" id="employee_type" name="employee_type"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select class="form-control" id="department_id" name="department_id" required>
                                            <option value="" selected disabled>Select Department</option>
                                            <option value="1">HR</option>
                                            <option value="2">IT</option>
                                            <option value="3">Finance</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary update-btn">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @endif

                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewModalLabel">Employee Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="employeeDetails">
                                <!-- User data will be dynamically loaded here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            </tbody>
        </table>
    </div>
</div>
@endsection
<script src=" https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.btn-danger', function(e) {
        e.preventDefault();

        var delete_btn = $(this);
        var row_delete = delete_btn.closest('tr');

        toastr.remove();
        $.ajax({
            url:  $(this).data('url'),
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success(response.message);
                row_delete.fadeOut(400, function() {
                    $(this).remove();
                });
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        toastr.error(errors[field].join(', '));
                    }
                } else {
                  toastr.error(xhr.responseJSON.message);

                }
            }
        });
    });
    $(document).on('click', '.edit-btn', function () {
        const employeeId = $(this).data('id');

        $.ajax({
            url: `edit/${employeeId}`,
            type: 'GET',
            success: function (response) {
                $('#emp_id').val(response.employee_get_by_id.employee_id);
                $('#employee_name').val(response.employee_get_by_id.employee_name);
                $('#salary').val(response.employee_get_by_id.salary);
                $('#designation').val(response.employee_get_by_id.designation);
                $('#employee_type').val(response.employee_get_by_id.employee_type);

                const departmentDropdown = $('#department_id');
                departmentDropdown.empty();
                departmentDropdown.append('<option value="" disabled>Select Department</option>');
               response.departments.forEach(function (department) {
                    const isSelected = department.department_id == response.employee_get_by_id.department_id ? 'selected' : '';
                    departmentDropdown.append(
                        `<option value="${department.department_id}" ${isSelected}>${department.department_name}</option>`
                    );
                });

                $('#updateForm').attr('action', `/employees/${employeeId}`);
            },
            error: function (xhr) {
                alert('Failed to fetch employee details.');
            }
        });
    });

    $(document).on('submit', '#updateForm', function (e) {
        e.preventDefault();
        const employeeId = $('#emp_id').val();
            toastr.remove();
            $.ajax({
                url: `update/${employeeId}`,
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

    $(document).on('click', '.btn-success', function(e) {
        e.preventDefault();

        const employeeId = $(this).data('url');

        $.ajax({
            url: employeeId,
            type: 'GET',
            success: function(response) {
                const employee = response.data;
                const employeeDetails = `
                    <p><strong>Employee Name:</strong> ${employee.employee_name}</p>
                    <p><strong>Email:</strong> ${employee.email}</p>
                    <p><strong>Salary:</strong> ${employee.salary}</p>
                    <p><strong>Designation:</strong> ${employee.designation}</p>
                    <p><strong>Employee Type:</strong> ${employee.employee_type}</p>
                    <p><strong>Department:</strong> ${employee.department_name}</p>
                `;

                $('#employeeDetails').html(employeeDetails);
                $('#viewModal').modal('show');
            },
            error: function(xhr) {
                alert('Failed to load employee details.');
            }
        });
    });





</script>