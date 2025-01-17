<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\Post\PostService;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $posts = $this->postService->getAllPosts();
        return PostResource::collection($posts);
    }

    public function store(PostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());
        return new PostResource($post);
    }

    public function show(Post $post)
    {
        $post = $this->postService->getPostById($post->id);
        return new PostResource($post);
    }

    public function update(PostRequest $request, Post $post)
    {
        try {
            $post = $this->postService->updatePost($post, $request->validated());
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $this->postService->deletePost($post);
            return response()->json(['message' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}