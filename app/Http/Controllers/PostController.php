<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // simpan image ke storage
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());

            // simpan ke database
            Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $image->hashName(),
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('articles.index')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('articles.index')->with('error', 'Post created failed.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //validasi data
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $post = Post::findOrFail($id);
         //update data
         DB::beginTransaction();
         try {
           
             // if image is not empty
             if ($request->file('image') != '') {
                 // store image
                 $image = $request->file('image');
                 $image->storeAs('public/images', $image->hashName());
                 // update
                 // delete image
                 Storage::disk('local')->delete('public/images/' . $post->image);
                 $post->update([
                     'title'     => $request->title,
                     'content'   => $request->content,
                     'image'     => $image->hashName(),
                 ]);
             } else {
                 // update
                 $post->update([
                     'title'     => $request->title,
                     'content'   => $request->content,
                 ]);
             }
 
             DB::commit();
             return redirect()->route('articles.index')->with('success', 'Post updated successfully.');

         } catch (\Exception $e) {
             DB::rollback();
             return redirect()->route('articles.index')->with('error', 'Post updated failed.');

         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete
        $post = Post::find($id);
        if($post){
            $post->delete();
            return redirect()->route('articles.index')->with('success', 'Post Deleted successfully.');

        }else{
            return redirect()->route('articles.index')->with('error', 'Post deleted failed.');

        }

        
    }
}
