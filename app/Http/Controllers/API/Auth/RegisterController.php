<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use DB;
class RegisterController extends Controller
{
    public function authLogin(){
        return response()->json([
            'status'=>401,
            'message'=>'You havent authorization in this website'
        ]);
    }
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());
       // try{
            $rules= [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],//paasword and password_confirmation
                'first_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'image' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'town' => 'required|string|max:255',
                'phone_no1' => 'required|string|max:255|unique:users',
                'phone_no2' => 'required|string|max:255|unique:users',
                'reg_info' => 'required',
            ];

            $data=$request->all();
            $validator=Validator::make($data,$rules);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>400,
                    'message'=>$validator->errors()
                    ]);
            }else{
                $user=new User();
                $user->email=$request->email;
                $user->password=Hash::make($request->password);
;
                $user->first_name=$request->first_name;
                $user->last_name=$request->last_name;
                $user->image=$request->image;
                $user->country=$request->country;
                $user->city=$request->city;
                $user->town=$request->town;
                $user->phone_no1=$request->phone_no1;
                $user->phone_no2=$request->phone_no2;
                $user->save();
                DB::table('role_users')->insert(['role_id'=>2,'user_id'=>$user['id']]);//role id =2 -> user
                event(new Registered($user));
                return response()->json([
                    'status'=>200,
                    'message'=>'registered successfully, you can make login now'
                ]);
            }

        // }catch(\Exception $ex){
        //     return response()->json([
        //         'status'=>500,
        //         'message'=>'There is something wrong, please try again'
        //     ]);  
        // } 
    }
}
