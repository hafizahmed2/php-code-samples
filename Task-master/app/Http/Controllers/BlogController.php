<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $Blog = Blog::Create([
            'title'=> $request->title,
            'description' => $request->description,
            'bloger_id' => \Auth::guard('bloger-api')->user()->id,
        ]);

        return response()->json([
            'success'=>true,
            'data' => 'Successfully created Blog!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return response()->json([
            'success'=>true,
            'data' => $blog
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
         return response()->json([
            'success'=>true,
            'data' => $blog
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $update = $blog->update(['title'=>$request->title,'description'=>$request->description]);

        if( $update){

        return response()->json([
            'success' => true,
            'data' => "Update Successfully",
        ],200);
        }else{

           return response()->json([
            'success' => true,
            'data' => "Cannot Update",
        ],400);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $delete = $blog->delete();

         if($delete){

        return response()->json([
            'success' => true,
            'data' => "Delete Successfully",
        ],200);
        }else{

           return response()->json([
            'success' => true,
            'data' => "Cannot delete",
        ],400);  
        }
    }
}
