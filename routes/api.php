<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('url/{url}/notepad', 'NotepadController');
Route::post('notepad/url/show', 'NotepadController@showNotepad');
Route::post('notepad/list', 'NotepadController@notepadList');
Route::post('notepad/password/authenticate', 'NotepadController@checkPasswordExist');
Route::post('url/{url}/notepad/text', 'NotepadController@returnText');
Route::post('url/password/remove', 'NotepadController@removeNotepadPassword');
Route::post('notepad/delete', 'NotepadController@deleteNotepad');
Route::get('users/{userId}/filedrop/shared/files/get', 'SharedFilesController@getSharedByMeFiles');
Route::get('users/{userId}/shared/files/get', 'SharedFilesController@getSharedWithMeFiles');
Route::post('users/shared/files/get', 'ShareFilesController@getSharedFiles');