<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    //Metodo para obtener todos los estudiantes 
    public function getAllStudents()
    {
        $estudiante = Student::all();
        if ($estudiante->isEmpty()) {
            $data = [
                'mensaje' => "No se encontraron estudiantes",
                'estado' => false,
                'error' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'mensaje' => "Estudiantes obtenidos exitosamente",
            'estado' => true,
            'estudiantes' => $estudiante
        ];
        return response()->json($data, 200);
    }
 
    //Metodo registrar un estudiante en la base de datos 
    public function createStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required'
        ]);
        //si los datos son invalidos 
        if ($validator->fails()) {
            $data = [
                'mensaje' => "Error en la validación de datos",
                'estado' => false,
                'error' => $validator->errors()
            ];
            return response()->json($data, 400);
        }
        //si los datos son validos 
        $estudiante = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);
        //si algo falla al registrar el estudiante
        if (!$estudiante) {
            $data = [
                'mensaje' => "Error al crear el estudiante",
                'estado' => false,
                'error' => 500
            ];
            return response()->json($data, 500);
        }
        //mensaje de confirmacion 
        $data = [
            'mensaje' => "Estudiante creado exitosamente",
            'estado' => true,
            'estudiante' => $estudiante
        ];
        return response()->json($data, 201);
    }

    //metodo para obtener un estudiante mediante su id
    public function getStudentById($id)
    {
        $estudiante = Student::find($id);
        if (!$estudiante) {
            $data = [
                'mensaje' => "Estudiante no encontrado",
                'estado' => false,
                'error' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'mensaje' => "Estudiante obtenido exitosamente",
            'estado' => true,
            'estudiante' => $estudiante
        ];
        return response()->json($data, 200);
    }

    //metodo para eliminar un estudiante mediante su id
    public function deleteStudent($id)
    {
        $estudiante = Student::find($id);
        if (!$estudiante) {
            $data = [
                'mensaje' => "Estudiante no encontrado",
                'estado' => false,
                'error' => 404
            ];
            return response()->json($data, 404);
        }
        if (!$estudiante->delete()) {
            $data = [
                'mensaje' => "Error al eliminar el estudiante",
                'estado' => false,
                'error' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'mensaje' => "Estudiante eliminado exitosamente",
            'estado' => true,
            'estudiante' => $estudiante
        ];
        return response()->json($data, 200);
    }

    //metodo para actualizar TODOS los campos de un estudiante
    public function updateStudent(Request $request, $id)
    {
        $estudiante = Student::find($id);
        if (!$estudiante) {
            $data = [
                'mensaje' => "Estudiante no encontrado",
                'estado' => false,
                'error' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => "required|email|unique:student",
            'phone' => 'required|digits:10',
            'language' => 'required'
        ]);
        if ($validator->fails()) {
            $data = [
                'mensaje' => "Error en la validación de datos",
                'estado' => false,
                'error' => $validator->errors()
            ];
            return response()->json($data, 400);
        }
        $estudiante->name = $request->name;
        $estudiante->email = $request->email;
        $estudiante->phone = $request->phone;
        $estudiante->language = $request->language;

        $estudiante->save();
        $data = [
            'mensaje' => "Estudiante actualizado exitosamente",
            'estado' => true,
            'estudiante' => $estudiante
        ];
        return response()->json($data, 200);
    }

    //metodo para actualizar PARCIALMENTE los campos de un estudiante
    public function updateStudentPartially(Request $request, $id)
    {
        $estudiante = Student::find($id);

        if (!$estudiante) {
            return response()->json([
                'mensaje' => "Estudiante no encontrado",
                'estado' => false,
                'error' => 404
            ], 404);
        }

        if (!$request->all()) {
            return response()->json([
                'mensaje' => "No se enviaron datos para actualizar",
                'estado' => false
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => "email|unique:students,email,$id",
            'phone' => 'digits:10',
            'language' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => "Error en la validación de datos",
                'estado' => false,
                'error' => $validator->errors()
            ], 400);
        }

        if ($request->has('name')) {
            $estudiante->name = $request->name;
        }
        if ($request->has('email')) {
            $estudiante->email = $request->email;
        }
        if ($request->has('phone')) {
            $estudiante->phone = $request->phone;
        }
        if ($request->has('language')) {
            $estudiante->language = $request->language;
        }

        $estudiante->save();
        DB::commit();

        return response()->json([
            'mensaje' => "Estudiante actualizado exitosamente",
            'estado' => true,
            'estudiante' => $estudiante
        ], 200);
    }
}
