<?php

namespace App\Services\Post;

use App\Models\Post;

class PostService
{
    public function getAllPosts()
    {
        return Post::with('user')->latest()->get();
    }

    public function getPostById($id)
    {
        return Post::with('user')->findOrFail($id);
    }

    public function createPost(array $data)
    {
        return auth()->user()->posts()->create($data);
    }

    public function updatePost(Post $post, array $data)
    {
        if ($post->user_id !== auth()->id()) {
            throw new \Exception('You can only edit your own posts');
        }

        $post->update($data);
        return $post->fresh();
    }

    public function deletePost(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            throw new \Exception('You can only delete your own posts');
        }

        $post->delete();
    }
}


