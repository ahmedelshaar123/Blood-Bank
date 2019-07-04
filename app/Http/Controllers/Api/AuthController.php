<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Mail\ResetPassword;
use App\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{




    public function register(Request $request){
        $validator = validator()->make($request->all(),[
            'name'=>'required',
            'email'=>'required|unique:clients',
            'date_of_birth'=>'required|date',
            'blood_type_id'=>'required|exists:blood_types,id',
            'city_id'=>'required|exists:cities,id',
            'phone'=>'required|unique:clients|digits:11',
            'password'=>'required|confirmed',
            'last_date_of_donation'=>'required|date|date:after:date_of_birth',



        ]);

        if($validator->fails()){
            return apiResponse(0, $validator->errors()->first(), $validator->errors());

        }
        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = str_random(60);
        $client->save();
        $client->governorates()->attach($client->city->governorate_id);
        $client->blood_types()->attach($request->blood_type_id);
        return apiResponse(1, "تمت الاضافة بنجاح", [
            'api_token'=>$client->api_token,
            'client'=>$client->load('city.governorate', 'blood_type'),
        ]);

    }


    public function login(Request $request){
        $validator = validator()->make($request->all(),[
           'phone'=>'required',
           'password'=>'required',
        ]);
        if($validator->fails()){
            return apiResponse(0, $validator->errors()->first(), $validator->errors());

        }
//        $auth = auth()->guard('api')->validate($request->all()); ??
//        return $this->apiResponse(1, "", $auth); ??

        $client = Client::where('phone', $request->phone)->first();
        if($client){
            if(Hash::check($request->password, $client->password)){
                return apiResponse(1, "تم تسجيل الدخول", ['api_token'=>$client->api_token,
                    'client'=>$client->load('city.governorate', 'blood_type')]);
            }else
                return apiResponse(0, "بيانات الدخول غير صحيحة");
        }else{
            return apiResponse(0, "بيانات الدخول غير صحيحة");
        }


    }

    public function profile(Request $request)
    {
        $validatior = validator()->make($request->all(), [
            'password' => 'confirmed',
            'email' => Rule::unique('clients')->ignore($request->user()->id),
            'phone' => Rule::unique('clients')->ignore($request->user()->id),
        ]);

        if ($validatior->fails()) {
            $data = $validatior->errors();
            return apiResponse(0,$validatior->errors()->first(),$data);
        }

        $loginUser = $request->user();
        $loginUser->update($request->all());


        if ($request->has('password'))
        {
            $loginUser->password = bcrypt($request->password);
        }

        $loginUser->save();

        if ($request->has('governorate_id'))
        {

            $loginUser->governorates()->detach($loginUser->city->governorate_id);
            $loginUser->governorates()->attach($loginUser->city->governorate_id);
        }

        $data = [
            'client' => $request->user()->fresh()->load('city.governorate','blood_type')
        ];
        return apiResponse(1,'تم تحديث البيانات',$data);
    }

    public function resetPassword(Request $request){
        $validator = validator()->make($request->all(), [
        'phone'=>'required'

        ]);

        if($validator->fails()){
            return apiResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $user = Client::where('phone', $request->phone)->first();
        if($user){
           $code = rand(1111, 9999);
            $update = $user->update(['pin_code'=> $code,
               ]);
            if($update){
                Mail::to($user->email)

                    ->send(new ResetPassword($user));






                return apiResponse(1, "برجاء فحص هاتفك", ['pin_code'=>$code,
                    'mail_fails'=>Mail::failures(),
                    'email'=>$user->email]);
            }else{
                return apiResponse(0, "حاول مرة أخري");
            }
        }else{
            return apiResponse(0, "الهاتف غير مسجل ");
        }
    }

    public function newPassword(Request $request){
        $validator = validator()->make($request->all(), [
           'pin_code'=>'required|alpha_num',
           'phone'=>'required|alpha_num',
           'password'=>'required|confirmed'
        ]);

        if($validator->fails()){
            return apiResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $user = Client::where('pin_code', $request->pin_code)->where('pin_code', '!=', 0)->where('phone', $request->phone)->first();
        if($user) {
            $user->password = bcrypt($request->password);
            $user->pin_code = null;
            if ($user->save()) {
                return apiResponse(1, "تم تغيير كلمة المرور بنجاح");
            }else{
                return apiResponse(0, "حدث خطأ حاول ثانية");
            }

        }else{
            return apiResponse(0, "الكود غير صالح");
        }
    }

    public function registerToken(Request $request){
        $validator = validator()->make($request->all(), [
           'token'=>'required',
           'platform'=>'required|in:android, ios',

        ]);

        if($validator->fails()){
            return apiResponse(0 , $validator->errors()->first(), $validator->errors());
        }

//        dd($request->all());
        Token::where('token', $request->token)->delete();
        $request->user()->tokens()->create($request->all());
        return apiResponse(1, "تم التسجيل بنجاح");
    }

    public function removeToken(Request $request){
        $validator = validator()->make($request->all(), [
           'token'=>'required',
        ]);

        if($validator->fails()){
            return apiResponse(0 , $validator->errors()->first(), $validator->errors());
        }

        Token::where('token', $request->token)->delete();
        return apiResponse(1, "تم المسح بنجاح");
    }

    public function notificationSettings(Request $request){
        $validator = validator()->make($request->all(), [
           'governorate_id.*'=>'required|exists:governorates,id',
           'blood_type_id.*'=>'required|exists:blood_types,id',
        ]);

        if($validator->fails()){
            return apiResponse(0, $validator->errors()->first(), $validator->errors());
        }

        if($request->has('governorate_id')){
            $request->user()->governorates()->sync($request->governorate_id);

        }

        if($request->has('blood_type_id')){
            $request->user()->blood_types()->sync($request->blood_type_id);
        }

        return apiResponse(1, "success", [
            'governorate_id'=> $request->user()->governorates()->pluck('governorate_id')->toArray(),
            'blood_type_id'=>$request->user()->blood_types()->pluck('blood_type_id')->toArray(),
        ]);

    }


}
