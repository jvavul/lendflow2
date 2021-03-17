<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TagController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['getAll', 'getById']]);
    }

    function create(Request $request) {
        $tag = new Tag();
        $tag->name = $request['name'];
        $tag->user_id = auth()->user()->id;
        $tag->save();

        return Response::json($tag,200);
    }

    function update(Request $request, $id) {
        $tag = Tag::find($id);

        // todo add consistent error handling
        if (!$tag) {
            return Response::json(['error' => 'tag not found'],400);
        }
        if ($tag->user_id != auth()->user()->id) {
            return Response::json(['error' => 'you do not own that tag'],400);
        }

        $tag->name = $request['name'];
        $tag->save();

        return Response::json($tag,200);
    }
    function delete(Request $request, $id) {
        $tag = Tag::find($id);

        // todo add consistent error handling
        if (!$tag) {
            return Response::json(['error' => 'file not found'],400);
        }
        if ($tag->user_id != auth()->user()->id) {
            return Response::json(['error' => 'you do not own that tag'],400);
        }
        $tag->delete();

        // todo do this with database cascade or model ******************************************************************
        DB::table('tag_posts')->where('tag_id', $id)->delete();

        return Response::json(['success'],200);
    }

    function getAll(Request $request) {
        $tags = Tag::all();
        return Response::json($tags,200);
    }

    function getById(Request $request, $id) {
        $tag = Tag::find($id);

        // todo add consistent error handling
        if (!$tag) {
            return Response::json(['error' => 'tag not found'],400);
        }

        return Response::json($tag,200);
    }
}
