<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ReadController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReadTempController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ExaminationsController;
use App\Http\Controllers\ConfigurationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/",[SiteController::class, "index"]);
Route::post("/import-from-xml",[ExaminationsController::class, "importExaminationsFromXML"]);

Route::get("/read/import",[ReadController::class, "importShow"]);
Route::post("/read/import",[ReadController::class, "import"]);
Route::post("/read/load-data-import",[ReadController::class, "getReadData"]);
Route::get("/read",[ReadController::class, "readShow"]);
Route::post("/read",[ReadController::class, "getDataRead"]);
Route::post("/read/update",[ReadController::class, "updateData"]);


Route::get("/read-temp",[ReadTempController::class, "showReadTemp"]);
Route::post("/read-temp",[ReadTempController::class, "createReadTemp"]);
Route::get("/read-temp/get-data",[ReadTempController::class, "getData"]);
Route::post("/read-temp/update-data",[ReadTempController::class, "updateReadTemp"]);
Route::post("/read-temp/add-to-top",[ReadTempController::class, "addToTop"]);


Route::post("/student/get-info", [StudentController::class, "getStudent"]);


Route::post("/inv/create", [InvoicesController::class, "createInv"]);
Route::get("/inv/get", [InvoicesController::class, "getInvByInfo"]);
Route::get("/inv/report", [InvoicesController::class, "reportInv"]);


Route::get("/configuration", [ConfigurationController::class, "getDataConfig"]);

Route::get("/test", [ConfigurationController::class, "test"]);
