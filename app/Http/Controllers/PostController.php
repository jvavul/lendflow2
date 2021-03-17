<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TagPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['getAll', 'getById']]);
    }

    function create(Request $request) {
        $post = new Post();
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->user_id = $request['owner']['id'];
        $post->file_id = $request['main_image']['id'];
        $post->save();

        // todo do this though model $post-> ... associate($tagPost);
        foreach ($request['tags'] AS $tag) {
            $tagPost = new TagPost();
            $tagPost->tag_id = $tag['id'];
            $tagPost->post_id = $post['id'];
            $tagPost->save();
        }

        $post->load(['tags', 'owner', 'main_image']);
        return Response::json($post,200);
    }

    function update(Request $request, $id) {
        $post = Post::find($id);

        // todo add consistent error handling
        if (!$post) {
            return Response::json(['error' => 'post not found'],400);
        }
        if ($post->user_id != auth()->user()->id) {
            return Response::json(['error' => 'you do not own that post'],400);
        }

        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->user_id = $request['owner']['id'];
        $post->file_id = $request['main_image']['id'];
        $post->save();

        // delete old tags
        DB::table('tag_posts')->where('post_id', $id)->delete();

        // add new tags
        foreach ($request['tags'] AS $tag) {
            $tagPost = new TagPost();
            $tagPost->tag_id = $tag['id'];
            $tagPost->post_id = $post['id'];
            $tagPost->save();
        }

        $post->load(['tags', 'owner', 'main_image']);
        return Response::json($post,200);
    }
    function delete($id) {
        $post = Post::find($id);

        // todo add consistent error handling
        if (!$post) {
            return Response::json(['error' => 'file not found'],400);
        }
        if ($post->user_id != auth()->user()->id) {
            return Response::json(['error' => 'you do not own that post'],400);
        }
        $post->delete();

        // todo do use database cascade or through model ******************************************************************
        DB::table('tag_posts')->where('post_id', $id)->delete();

        return Response::json(['success'],200);
    }

    function getAll() {
        $posts = Post::with(['tags', 'owner', 'main_image'])
            ->get();
        return Response::json($posts,200);
    }

    function getById($id) {
        $post = Post::with(['tags', 'owner', 'main_image'])
            ->find($id);

        // todo add consistent error handling
        if (!$post) {
            return Response::json(['error' => 'post not found'],400);
        }

        return Response::json($post,200);
    }
}
