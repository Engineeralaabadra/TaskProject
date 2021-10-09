<?php

namespace App\Http\Controllers\API\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return 11;
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try{
            $rules= [
                'email' => 'required|string|email|max:255',
                'password' => ['required', Rules\Password::defaults()]
            ];
            $data=$request->all();
            $validator=Validator::make($data,$rules);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>400,
                    'message'=>$validator->errors()
                    ]);
            }
            $user = User::where(['email'=> $request->email])->first();
            if(!$user){
                return response()->json([
                    'status'=>400,
                    'message'=>'This user not LoggedIn'
                ]);
            }else{
                if (! Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status'=>400,
                        'message'=>'your password are incorrect.'
                    ]);
                }else{
                    $token = $user->createToken($request->device_name);
                    $accessToken=$user->tokens()->latest()->first();
                    $accessToken->forceFill([//to force fill ip field in this table and we will make migrate rollback after this
                        'ip'=>$request->ip()
                    ])->save();
                    return response()->json([
                        'status'=>200,
                        'message'=>'logged in successfully, welcome into this website',
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'user'=>$user,
                        'device_name'=>$request->device_name,
                        'ip'=>$request->ip()
                    ]);
                    
                }
            }
        }catch(\Exception $ex){
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);  
        } 
        
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        try{
            $user=Auth::guard('sanctum')->user();
            //delete all tokens for this user from all devices 
            $user->tokens()->delete();
            //delete special token for this user from 
           // $user->tokens()->find($id)->delete();
            //delete token this user from current device
            $user->currentAccessToken()->delete();
            return response()->json([
                'status'=>200,
                'message'=>'logout successfully'
            ]); 
        }catch(\Exception $ex){
           return response()->json([
               'status'=>500,
               'message'=>'There is something wrong, please try again'
           ]);  
        } 
    }
}
