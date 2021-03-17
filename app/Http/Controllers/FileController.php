<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['getAll', 'getById']]);
    }

    function create(Request $request) {
        $file = new File();

        // todo add validation for existing email
        $file->url = $request['url'];
        $file->user_id = auth()->user()->id;
        $file->save();

        return Response::json($file,200);
    }

    function delete(Request $request, $id) {
        $file = File::find($id);

        // todo add consistent error handling
        if (!$file) {
            return Response::json(['error' => 'file not found'],400);
        }
        if ($file->user_id != auth()->user()->id) {
            return Response::json(['error' => 'you do not own that file'],400);
        }
        $file->delete();

        return Response::json(['success'],200);
    }
    function getAll(Request $request) {
        $files = File::all();
        return Response::json($files,200);
    }
    function getById(Request $request, $id) {
        $file = File::find($id);

        // todo add consistent error handling
        if (!$file) {
            return Response::json(['error' => 'file not found'],400);
        }

        return Response::json($file,200);
    }

}
