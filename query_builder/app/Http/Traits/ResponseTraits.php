<?php
    namespace App\Http\Traits;
    use Illuminate\Support\Str;
    trait ResponseTraits{
        public function sendErrorResponse($validation)
        {
            return response()->json(['status'=>false,'message'=>'Validation Error','Error'=>$validation->errors()],422);    
        }

        public function sendSuccessResponse($status,$message,$data="")
        {
            return response()->json(['status'=>$status,'message'=>$message,'data'=>$data],200);
        }
        public function sendExecptionMessage($ex)
        {
            $boolean = Str::contains($ex->getMessage(), 'Duplicate entry');
           if($boolean)
           {
                return response()->json(['status'=>false,'message'=>"Data Duplicate Error"],500);
           }
           else
           {
                return response()->json(['status'=>false,'message'=>$ex->getMessage()],500);
           }
        }
    }


?>