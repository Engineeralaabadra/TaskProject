<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $authPermission=$this->authorize('viewAny',User::class);
        // if($authPermission){
        try{
            $users=User::with(["rolesUser"])->get();
            if(!empty($users)){
                return response()->json([
                    'status'=>200,
                    'message'=>$users
                ]);  
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'there is no data'
                ]);
            }
        }catch(\Exception $ex){
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);  
        }
    // }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $authPermission=$this->authorize('create',User::class);
        // if($authPermission){
            $rules= [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],//password and password_confirmation
                'first_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'image' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_no1' => 'required|string|max:255|unique:users',
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
        try{
            $data=$request->all();
            DB::beginTransaction();
            $user=new User();
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->image=$request->image;
            $user->country=$request->country;
            $user->address=$request->address;
            $user->phone_no1=$request->phone_no1;
            $user->phone_no2=$request->phone_no2;
            $user->save();
            DB::table('role_users')->insert(['role_id'=>2,'user_id'=>$user['id']]);//role id =2 -> user

            DB::commit();
            return response()->json([
                'status'=>200,
                'message'=>'added new User succefully'
            ]);
         }catch(\Exception $ex){
             DB::rollback();
             return response()->json([
                 'status'=>500,
                 'message'=>'There is something wrong, please try again'
             ]);
         }
        }
    // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::findOrFail($id);
        // $authPermission=$this->authorize('view',$user);
        // if($authPermission){
        try{
            DB::beginTransaction();
           $user= User::find($id)->rolesUser()->get();
            if(!empty($user)){
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message'=>$user
                ]);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'there is no data'
                ]);
            }
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);
        }
    // }
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
        DB::beginTransaction();
        $user=User::findOrFail($id);
        // $authPermission=$this->authorize('update',$user);
        // if($authPermission){
        try{
            $user=User::findOrFail($id);
            if(!$user){
                return response()->json([
                    'status'=>404,
                    'message'=>'This User id not exist to update it'
                ]);
            }else{
                $data=$request->all();
                $rules= [
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],//password and password_confirmation
                    'first_name' => 'required|string|max:255',
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'image' => 'required|string|max:255',
                    'country' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'phone_no1' => 'required|string|max:255',
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
                    $data=$request->all();
                    $user->email=$request->email;
                    $user->password=Hash::make($request->password);
                    $user->first_name=$request->first_name;
                    $user->last_name=$request->last_name;
                    $user->image=$request->image;
                    $user->country=$request->country;
                    $user->address=$request->address;
                    $user->phone_no1=$request->phone_no1;
                    $user->phone_no2=$request->phone_no2;
                    $user->save();
                    DB::table('role_users')->update(['role_id'=>2,'user_id'=>$user['id']]);//role id =2 -> user
                    DB::commit();
                    return response()->json([
                        'status'=>200,
                        'message'=>'updated'.$user->first_name.''.$user->last_name.'succefully'
                    ]);
            }
        }
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);
        }
        //}
  //  }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user=User::findOrFail($id);
        // $authPermission=$this->authorize('delete',$user);
        // if($authPermission){
        try{
            $user=User::findOrFail($id);
            if(!$user){
                return response()->json([
                    'status'=>404,
                    'message'=>'This User id not exist'
                ]);
            }else{
                DB::beginTransaction();
                $user->delete();
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message'=>'deleted this User succefully'
                ]);
            }
            
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);
        }
    // }
    }
}
