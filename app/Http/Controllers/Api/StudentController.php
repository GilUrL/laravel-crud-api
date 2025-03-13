<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Estudiantes",
 *     version="1.0",
 *     description="Documentación de la API para gestionar estudiantes",
 *     @OA\Contact(
 *         email="soporte@tuapi.com"
 *     )
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     summary="Obtiene la lista de todos los estudiantes",
     *     tags={"Estudiantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de estudiantes obtenida exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron estudiantes"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/students",
     *     summary="Registra un nuevo estudiante",
     *     tags={"Estudiantes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "language"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="language", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estudiante creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación de datos"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     summary="Obtener un estudiante por ID",
     *     tags={"Estudiantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante obtenido exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante obtenido exitosamente"),
     *             @OA\Property(property="estado", type="boolean", example=true),
     *             @OA\Property(property="estudiante", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante no encontrado"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="integer", example=404)
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     summary="Eliminar un estudiante por ID",
     *     tags={"Estudiantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante eliminado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante eliminado exitosamente"),
     *             @OA\Property(property="estado", type="boolean", example=true),
     *             @OA\Property(property="estudiante", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante no encontrado"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el estudiante",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Error al eliminar el estudiante"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="integer", example=500)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     summary="Actualizar todos los campos de un estudiante",
     *     tags={"Estudiantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "language"},
     *             @OA\Property(property="name", type="string", example="Maria Chavez"),
     *             @OA\Property(property="email", type="string", format="email", example="Maria@example.com"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="language", type="string", example="Español")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante actualizado exitosamente"),
     *             @OA\Property(property="estado", type="boolean", example=true),
     *             @OA\Property(property="estudiante", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Error en la validación de datos"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante no encontrado"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="integer", example=404)
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Patch(
     *     path="/api/students/{id}",
     *     summary="Actualizar parcialmente los campos de un estudiante",
     *     tags={"Estudiantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Maria Chavez"),
     *             @OA\Property(property="email", type="string", format="email", example="Maria@example.com"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="language", type="string", example="Español")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante actualizado exitosamente"),
     *             @OA\Property(property="estado", type="boolean", example=true),
     *             @OA\Property(property="estudiante", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación de datos o no se enviaron datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Error en la validación de datos"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Estudiante no encontrado"),
     *             @OA\Property(property="estado", type="boolean", example=false),
     *             @OA\Property(property="error", type="integer", example=404)
     *         )
     *     )
     * )
     */

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
            'email' => "email",
            'phone' => 'digits:10',
            'language' => ''
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
