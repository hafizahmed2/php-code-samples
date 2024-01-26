<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;
use Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return response()->json([
            'success' => true,
            'data' => $user,
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
       $validation = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validation->fails()){
            return response()->json(['success'=>false,'data'=>$validation->messages()]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'User'
        ]);

        return response()->json([
            'success'=>true,
            'data' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->get();

        return response()->json([
            'success' => true,
            'data' => $user,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id',$id)->get();

        return response()->json([
            'success' => true,
            'data' => $user,
        ],200);
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
        $user = User::where('id',$id)->first();

         $update = $user->update(['name' => $request->name,
            'email' => $request->email,]);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id',$id)->first();

        $delete = $user->delete();

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

    public function login(Request $request)
    {
        
        $validation = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if($validation->fails()){
            return response()->json(['success'=>false,'data'=>$validation->messages()],422);
        }

        /*  API GUARD

         You cannot use attempt() method on api guards, it just doesnt exist. You NEED to use Auth::guard(web)->attempt() or use your own logic and issue your tokens from the response */

        $user = User::where('email', $request->email)->first();

        if (! $user){
            return response()->json([
                'message' => 'User not exists'
            ], 401);
            
        }

        $check = Hash::check($request->password, $user->password);

        if (! $check){
            return response()->json([
                'message' => 'Email or Password is not correct'
            ], 401);
            
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addDays(1);

        $token->save();

     $user->access_token = $tokenResult->accessToken;

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
