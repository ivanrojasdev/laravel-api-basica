<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = Employee::paginate($request->input('per_page', 10));
        return response()->json($employees, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        return response()->json([
            'message' => 'Employee created successfully.',
            'data' => $employee
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Buscar el empleado por su ID
        $employee = Employee::find($id);
        // Si no se encuentra el empleado, lanzar una excepcion
        if (!$employee) {
            throw new ModelNotFoundException("Employee not found with id {$id}");
        }
        return response()->json($employee, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, string $id)
    {
        //Buscar el empleado por su ID
        $employee = Employee::find($id);
        if (!$employee) {
            throw new ModelNotFoundException("Employee not found with id {$id}");
        }

        $employee->update($request->validated());

        return response()->json([
            "message" => "Employee updated successfully.",
            "data" => $employee
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            throw new ModelNotFoundException("Employee not found with id {$id}");
        }

        $employee->delete();
        return response()->noContent();
    }
}
