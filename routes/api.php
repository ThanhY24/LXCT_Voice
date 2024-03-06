<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReadController;
use App\Http\Controllers\StudentController;

Route::post("/read/import",[ReadController::class, "import"]);
Route::post("/student/get-info", [StudentController::class, "getStudent"]);