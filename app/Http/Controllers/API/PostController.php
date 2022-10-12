<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return json
        return response()->json(
            [
                'success'   => true,
                'message'   => 'List of articles',
                'data'      => Post::paginate(10)
            ],
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // validator
            $validator = Validator::make($request->all(), [
                'title'     => 'required',
                'content'   => 'required',
                'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id'   => 'required',
            ], [
                'title.required'    => 'Title is required',
                'content.required'  => 'Content is required',
                'image.required'    => 'Image is required',
                'user_id.required'  => 'User is required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Please fill all the fields',
                    'data'      => $validator->errors()
                ], 422);
            }

            // store image
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());
            // create
            $post = Post::create([
                'title'     => $request->title,
                'content'   => $request->content,
                'image'     => $image->hashName(),
                'user_id'   => $request->user_id,
            ]);

            DB::commit();
            return response()->json([
                'success'   => true,
                'message'   => 'Article created',
                'data'      => $post
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to create article',
                'data'      => $e->getMessage() . ' ' . $e->getLine(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update data
        DB::beginTransaction();
        try {
            // validator
            $validator = Validator::make($request->all(), [
                'title'     => 'required',
                'content'   => 'required',
                'user_id'   => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Please fill all the fields',
                    'data'      => $validator->errors()
                ], 422);
            }
            // if image is not empty
            if ($request->file('image') != '') {
                // store image
                $image = $request->file('image');
                $image->storeAs('public/images', $image->hashName());
                // update
                $post = Post::findOrFail($id);
                // delete image
                Storage::disk('local')->delete('public/images/' . $post->image);
                $post->update([
                    'title'     => $request->title,
                    'content'   => $request->content,
                    'image'     => $image->hashName(),
                    'user_id'   => $request->user_id,
                ]);
            } else {
                // update
                $post = Post::findOrFail($id);
                $post->update([
                    'title'     => $request->title,
                    'content'   => $request->content,
                    'user_id'   => $request->user_id,
                ]);
            }

            DB::commit();
            return response()->json([
                'success'   => true,
                'message'   => 'Article updated',
                'data'      => $post
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to update article',
                'data'      => $e->getMessage() . ' ' . $e->getLine(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete article
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($id);
            // delete image
            Storage::disk('local')->delete('public/images/' . $post->image);
            $post->delete();

            DB::commit();
            return response()->json([
                'success'   => true,
                'message'   => 'Article deleted',
                'data'      => $post
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to delete article',
                'data'      => $e->getMessage() . ' ' . $e->getLine(),
            ], 500);
        }
    }
}
