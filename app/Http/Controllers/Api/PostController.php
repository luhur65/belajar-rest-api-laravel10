<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    // get all data
    public function index(): PostResource
    {
        $posts = Post::latest()->paginate(5);
        return new PostResource($posts, 'List data posts', 'success');
    }

    // store data
    public function store(Request $request): PostResource
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required',
        ]);

        // if validation failed
        if ($validator->fails()) {
            return new PostResource($validator->errors(), 'Validation error', 'error');
        }

        // if validation success
        // upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // create post
        $post = Post::create([
            'title' => $request->title,
            'image' => $image->hashName(),
            'content' => $request->content,
        ]);

        // return response
        return new PostResource($post, 'Post created!', 'success');
    }

    // show single data
    public function show(Post $post): PostResource
    {
        return new PostResource($post, 'Detail data post', 'success');
    }

    // update data
    public function update(Request $request, Post $post): PostResource
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required',
        ]);

        // if validation failed
        if ($validator->fails()) {
            return new PostResource($validator->errors(), 'Validation error', 'error');
        }

        // if validation success
        // upload image
        if ($request->hasFile('image')) {
            // delete old image
            Storage::disk('local')->delete('public/posts/' . $post->image);

            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            $post->update([
                'title' => $request->title,
                'image' => $image->hashName(),
                'content' => $request->content,
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        // return response
        return new PostResource($post, 'Post updated!', 'success');
    }

    // delete data
    public function destroy(Post $post): PostResource
    {
        // delete image
        Storage::disk('local')->delete('public/posts/' . $post->image);

        // delete post
        $post->delete();

        // return response
        return new PostResource(null, 'Post deleted!', 'success');
    }
}
