<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;
use App\Http\Resources\PostResource;



class PostController extends Controller
{

    function __construct(){
        $this->middleware('auth:sanctum')->only(["store", "update"]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::all();
        return PostResource::collection($posts);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // return Auth::id();
        $request_data = $request->except('slug'); // Execlde slug from request data
        // $request_data = $request->validated();
        $image_path= null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_path=$image->store("", 'posts_images');
        }
        $request_data= request()->all();
        $request_data['creator_id'] = Auth::id();
        $request_data['image'] = $image_path; # replace image object with image_uploaded path
        //save data to DB using mass assignment
        $post = Post::create($request_data);

        return new PostResource($post);
        
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        // return $post;
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $request_data = $request->except('slug');
        $image_path= $post->image;
        if($request->hasFile('image')){
            # delete old_image
            Storage::disk('posts_images')->delete($image_path);
            $image = $request->file('image');
            $image_path=$image->store("", 'posts_images');
        }
        $request_data= request()->all();
        $request_data['image']=$image_path; # replace image object with image_uploaded path

        //save data to DB using mass assignment
        $post->update($request_data);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();
        return "deleted";
    }
}
