<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{

    public function showSignUpForm()
    {
        $departments = Department::query()
            ->select('department_id', 'department_name')
            ->get();
        return view('employee.sign_up', ['departments' => $departments]);
    }

    public function signUp(Request $request)
    {

        $request->validate(
            [
                'employee_name' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,department_id',
                'salary' => 'required|numeric',
                'designation' => 'required|string|max:255',
                'employee_type' => 'required|in:admin,employee',
                'email' => 'required|email|unique:employees,email',

                'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'],
                'password_confirmation' => 'required|same:password',
            ],
            [
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 6 characters.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
            ]
        );

        try {

            Employee::create([
                'employee_name' => $request->employee_name,
                'department_id' => $request->department_id,
                'salary' => $request->salary,
                'designation' => $request->designation,
                'employee_type' => $request->employee_type,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Employee created successfully!',
                    'redirect_url' => route('sign-in'),
                ], 200);
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while creating the employee.',
                    'error' => $e->getMessage(),
                    'redirect_url' => route('sign-up'),
                ], 500);
            }
        }
    }

    public function showLoginForm()
    {
        return view('employee.sign_in');
    }

    public function SignIn(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ],
            [
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Password is required.',
            ]
        );

        $credentials = $request->only('email', 'password');
        if (Auth::guard('employee')->attempt($credentials)) {

            if (Auth::guard('employee')->user()->employee_type == "employee") {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thank you for signing in! We are glad to see you back.',
                    'redirect_url' => route('employee-info'),
                    // 'user' => Auth::guard('employee')->user(),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thank you for signing in! We are glad to see you back.',
                    'redirect_url' => route('dashboard'),
                    // 'user' => Auth::guard('employee')->user(),
                ], 200);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'You have entered invalid credentials.'
        ], 401);
    }


    public function employeeInfo()
    {
        if (!Auth::guard('employee')->check()) {
            return redirect('/sign-in');
        }

        $employee = Employee::query()
            ->join('departments as d', 'd.department_id', '=', 'employees.department_id')
            ->select('employee_id', 'email', 'employee_name', 'salary', 'designation', 'employee_type', 'department_name')
            ->where('employees.department_id', Auth::guard('employee')->user()->department_id)
            ->first();

        return view('user-info', ['employee' => $employee]);
    }
}
