<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\People;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    use ResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try {
        
        $data = DB::table('people')->where('deleted_at',null)->get();
        return $this->sendSuccessResponse(200,"People Data Get Successfully",$data);

       } catch (Exception $th) {
            return $this->sendExecptionMessage($th);
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gender = array('Male','Female','Other');
        $todayDate = date('Y/m/d');
        $validation = validator($request->all(),[
            'people_name'       => 'required',
            'people_email'      => 'required|email|unique:people',
            'people_number'     => 'required|digits:10|unique:people',
            'people_address'    => 'required',
            'people_birthdate'  => 'required|before:-18 year',
            'people_gender'     => 'required|in:'. implode(',',$gender),
            'marriage_status'   => 'required'
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        // DB::table('people')->insert([
        //     'people_name'       => $request->people_name,
        //     'people_email'      => $request->people_email,
        //     'people_number'     => $request->people_email,
        //     'people_address'    => $request->people_address,
        //     'people_birthdate'  => $request->people_birthdate,
        //     'people_gender'     => $request->people_gender,
        //     'people_gender'   => $request->people_gender
        // ]);
        $request['created_at'] = now();
        $request['updated_at'] = now();
        DB::table('people')->insert($request->all());
        return $this->sendSuccessResponse(200,'People Add Successfully',$request->people_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($people)
    {
        try{
            $data = DB::table('people')->find($people);
            return $this->sendSuccessResponse(200,'People Data Get Successfully',$data);

        }catch(Exception $th){
            return $this->sendExecptionMessage($th);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try
        {
            $gender = array('Male','Female','Other');
            $todayDate = date('Y/m/d');
            $validation = validator($request->all(),[
                'people_name'       => 'required',
                'people_address'    => 'required',
                'people_email'      => 'required|email',
                'people_birthdate'  => 'required|before:-18 year',
                'people_gender'     => 'required|in:'. implode(',',$gender),
                'marriage_status'   => 'required'
            ]);

            if($validation->fails())
            {
                return $this->sendErrorResponse($validation);
            }
            $request['updated_at'] = now();
            $data = DB::table('people')->where('id',$id)->updateOrInsert($request->all());
            //$data = DB::table('people')->where('id',$id)->update($request->all());
            if($data){
                return $this->sendSuccessResponse(200,'Data Updated Successfully',$data);
            }else{
                return $this->sendSuccessResponse(404,'Data Not Found',$data);
            }
        }
        catch(Exception $th)
        {
            return $this->sendExecptionMessage($th);
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
        try
        {
            $data = DB::table('people')->where('id',$id)->delete();
            if($data){
                return $this->sendSuccessResponse(200,'Data Deleted Successfully',$data);
            }else{
                return $this->sendSuccessResponse(404,'Data Not Found',$data);
            }
        }catch(Exception $th)
        {
            return $this->sendExecptionMessage($th);
        }
    }

    public function softDelete($id)
    {
        $data = DB::table('people')->where('id',$id)->update(['deleted_at'=>now()]);
        if($data){
            return $this->sendSuccessResponse(200,'Data Deleted Successfully',$data);
        }else{
            return $this->sendSuccessResponse(404,'Data Not Found',$data);
        }
    }
}
