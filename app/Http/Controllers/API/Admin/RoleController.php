<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use DB;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $authPermission=$this->authorize('view-any',Role::class);
        // if($authPermission){
        try{
            $roles=Role::with(["usersRole","permissionsRole"])->paginate(10);
            if(!empty($roles)){
                return response()->json([
                    'status'=>200,
                    'message'=>$roles
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
        // $authPermission=$this->authorize('create',Role::class);
        // if($authPermission){
         try{
            $data=$request->all();
            DB::beginTransaction();
            Role::insert(['name'=>$data['name'],'role_status'=>$data['role_status']]);
            DB::commit();
            return response()->json([
                'status'=>200,
                'message'=>'added new Role succefully'
            ]);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role=Role::findOrFail($id);
        // $authPermission=$this->authorize('view',$role);
        // if($authPermission){
        try{
            DB::beginTransaction();
            $role=Role::findOrFail($id)->usersRole;
            if(!empty($role)){
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message'=>$role
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
        $role=Role::findOrFail($id);
        // $authPermission=$this->authorize('update',$role);
        // if($authPermission){
        try{
            $role=Role::findOrFail($id);
            if(!$role){
                return response()->json([
                    'status'=>404,
                    'message'=>'This Role id not exist'
                ]);
            }else{
                $data=$request->all();
                DB::beginTransaction();
                Role::where(['id'=>$id])->update(['Role_code'=>$data['Role_code'],'coupon_code_amount_type'=>$data['coupon_code_amount_type'],'coupon_code_amount'=>$data['coupon_code_amount'],'coupon_code_expiry_date'=>$data['coupon_code_expiry_date'],'coupon_code_status'=>$data['coupon_code_status']]);
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message'=>'updated'.$role->name.'succefully'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $role=Role::findOrFail($id);
        // $authPermission=$this->authorize('delete',$role);
        // if($authPermission){
        try{
            $role=Role::findOrFail($id);
            if(!$role){
                return response()->json([
                    'status'=>404,
                    'message'=>'This Role id not exist'
                ]);
            }else{
                DB::beginTransaction();
                $role->delete();
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message'=>'deleted this Role succefully'
                ]);
            }
            
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status'=>500,
                'message'=>'There is something wrong, please try again'
            ]);
        }
    //}
    }
}
