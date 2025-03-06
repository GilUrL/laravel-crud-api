<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;

//Obtener todos los estudiantes registrados
Route::get('/students', [StudentController::class,'getAllStudents']);

//Registrar un nuevo estudiante
Route::post('/students', [StudentController::class, 'createStudent']);

//Obtener un solo estudiante mediante su id
Route::get('/students/{id}', [StudentController::class, 'getStudentById']);

//actualizar un estudiante 
Route::put('/students/{id}', [StudentController::class, 'updateStudent']);

//Actualizar un estudiante parcialmente (actualizar solo algunos campos)
Route::patch('/students/{id}', [StudentController::class, 'updateStudentPartially']);

//Eliminar un estudiante por su id
Route::delete('/students/{id}', [StudentController::class, 'deleteStudent']);