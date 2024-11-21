<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (!Auth::guard('employee')->check()) {
            return redirect('/sign-in');
        }

        $employees = Employee::query()
            ->join('departments as d', 'd.department_id', '=', 'employees.department_id')
            ->select('employee_id', 'employee_name', 'salary', 'designation', 'employee_type', 'department_name')
            ->where('employee_type', "employee")
            ->get();
        return view('employee.dashboard', ['employees' => $employees]);
    }

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function edit($id)
    {

        $departments = Department::get();
        $employee = Employee::findOrFail($id);

        if ($employee) {
            return response()->json(
                [
                    'employee_get_by_id' => $employee,
                    'departments' => $departments
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'No Data Found',
                ]
            );
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'employee_name' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,department_id',
                'salary' => 'required|numeric',
                'designation' => 'required|string|max:255',
                'employee_type' => 'required|in:admin,employee',
            ],

        );

        try {

            Employee::where('employee_id', $id)->update([
                'employee_name' => $request->employee_name,
                'department_id' => $request->department_id,
                'salary' => $request->salary,
                'designation' => $request->designation,
                'employee_type' => $request->employee_type,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Employee Update Successfully!',
                    'redirect_url' => route('dashboard'),
                ], 200);
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while creating the employee.',
                    'error' => $e->getMessage(),
                    'redirect_url' => route('dashboard'),
                ], 500);
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'error',
            'message' => 'You have been logged out successfully.',
            'redirect_url' => route('sign-in'),
        ], 200);
    }

    public function view($id)
    {
        $employee = Employee::query()
            ->join('departments as d', 'd.department_id', '=', 'employees.department_id')
            ->select('employee_id', 'email', 'employee_name', 'salary', 'designation', 'employee_type', 'department_name')
            ->where('employees.employee_id', $id)
            ->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json(['data' => $employee]);
    }
}
