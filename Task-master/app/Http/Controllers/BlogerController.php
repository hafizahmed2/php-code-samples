<?php

namespace App\Http\Controllers;

use App\Bloger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Hash;

class BlogerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Bloger::all();

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
            'email' => 'required|string|email|unique:blogers',
            'password' => 'required|string|min:8'
        ]);

        if($validation->fails()){
            return response()->json(['success'=>false,'data'=>$validation->messages()]);
        }

        $user = Bloger::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'Bloger'
        ]);

        return response()->json([
            'success'=>true,
            'data' => 'Successfully created Bloger!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bloger  $bloger
     * @return \Illuminate\Http\Response
     */
    public function show(Bloger $bloger)
    {
        return response()->json([
            'success' => true,
            'data' => $bloger,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bloger  $bloger
     * @return \Illuminate\Http\Response
     */
    public function edit(Bloger $bloger)
    {
        return response()->json([
            'success' => true,
            'data' => $bloger,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bloger  $bloger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bloger $bloger)
    {
        $update = $bloger->update(['name' => $request->name,
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
     * @param  \App\Bloger  $bloger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bloger $bloger)
    {
        $delete = $admin->delete();

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

       $user = Bloger::where('email', $request->email)->first();

       if (! $user){
            return response()->json([
                'message' => 'User not exists'
            ], 400);
            
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
        ],200);
    }
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
