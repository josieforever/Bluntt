<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function search($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    public function editPost(Post $post, Request $request) {
        if (auth()->user()->can('update', $post)) {
            $incomingFields = $request->validate(['title' => 'required', 'body' => 'required']);
            $incomingFields['title'] = strip_tags($incomingFields['title']); 
            $incomingFields['body'] = strip_tags($incomingFields['body']); 
            $post->update($incomingFields);
            return redirect("/post/{$post->id}")->with('success', 'post updated successfully');
        } else {
            return;
        }
        

    }

    public function editRoute(Post $post) {
        if (auth()->user()->can('update', $post)) {
            return view('edit-post', ['post'=>$post]);
        } else {
            return;
        }
        
    }

    /* this is function leverages the PostPolicy we created by calling the can method on the user method */
    /* if the condition should return true we can execute the delete function on the that specific post object */
    public function deletePost(Post $post) {
        if (auth()->user()->can('delete', $post)) {
            $post->delete();/* achieved by creating a policy in the image of a Post::class */
            return redirect('/profile/'.auth()->user()->username )->with('success', 'post deleted successfully');
        } else {
            return;
        }
            
    }

    /* laravel can do databe look ups for us when we make the agument names match */
    public function showSinglePost(Post $post) {//type hinting is used to create the post object
        /* laravel returns the $post object you are looking for based on the parameters that was passed into the function from the route */
        /* meaning we dont have to query the database ourselves */
        return view('single-post', [
            'post' => $post, 
            'username' => $post->user->username,
            'dateCreated'=>$post->created_at->format('n/j/Y')
        ]);/* here, the view helper function has been passed a second argument*/
    }

    public function storeNewPost(Request $request) {
        /* here we call the validate method on the incoming fields variable where we spell out rules that the fields must 
        comply with */
        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);
        /* this is part where the fields are stripped-off of any html tags for better security  */
        $incomingFields['title'] =  strip_tags($incomingFields['title']); 
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        /* the Post::create() returns an object so we store that object in a variable then we can access the id fieldin the object */
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with('success', 'Post Created');
    }

    public function showCreateForm() {
        return view('create-post');
    }
}
