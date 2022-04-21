<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Controllers\GeneralController;
use App\Http\Requests\Api\ForgerPasswordRequest;
use App\Mail\CodeMail;
use App\Http\Resources\UserResources;
use App\Models\User;
use App\Models\VerfiyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function auth;
use function msgdata;
use function response;

class UserController extends GeneralController
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:100|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
            'lat' => 'required|max:255',
            'lng' => 'required|max:255',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse( $validator->messages()->first(), null , 400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'password' => $request->password
        ]);
        return $this->sendResponse($user, __('lang.user_successfully_registered'), 200);
    }

    public function update_profile(Request $request)
    {
        $id = auth('api')->user()->id;
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'phone' => 'required|numeric|unique:users,phone,' . $id,
            'lat' => 'required|max:255',
            'lng' => 'required|max:255',
            'password' => 'required|string|confirmed|min:6',
        ]);
        unset($data['password_confirmation']);
        if ($validator->fails()) {
            return $this->errorResponse( $validator->messages()->first(), null , 400);
        }
        User::where('id', $id)->update($data);
        $user = User::where('id', $id)->first();
        return $this->sendResponse($user, __('lang.user_profile_updated_successfully'), 200);
    }

    public function profile(Request $request)
    {
        $id = auth('api')->user()->id;
        $user = User::where('id', $id)->first();
        $data = (new UserResources($user));
        return $this->sendResponse($data, __('lang.data_show_successfully'), 200);
    }

    public function forget_password_code(ForgerPasswordRequest $request)
    {
        $data = $request->validated();
        $code = rand(100000, 999999);
        VerfiyCode::create([
            'code' => $code,
            'email' => $request['email']
        ]);
        $details = [
            'title' => 'MyKom',
            'body' => 'thank you for register , your code is : ' . $code,
        ];
        $newpassword = bcrypt($code);
        User::where('email', $data['email'])->update([
            'password' => $newpassword
        ]);
        Mail::to($request['email'])->send(new CodeMail($details));
        return $this->sendResponse($request['email'], __('lang.code_sent_to_email_successfully'), 200);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse( $validator->messages()->first(), null , 400);
        }
        $credentials = $request->only(['email', 'password']);
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return $this->errorResponse( __('lang.login_data_not_correct'), null , 401);
        }
        $user = Auth::guard('api')->user();
        if (!$token) {
            return $this->errorResponse( __('lang.login_data_not_correct'), null , 401);
        }
        if ($user->status == 'unactive') {
            Auth::guard('api')->logout();
            return $this->errorResponse( __('lang.you_are_not_active'), null , 406);
        }
        $user_data = User::where('id', $user->id)->first();
        $user_data->token_api = $token;
        return $this->sendResponse($user_data, __('lang.login_s'), 200);
    }


    public function logout()
    {
        auth('api')->logout();
        return $this->sendResponse((object)[], __('lang.logout_s'), 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}
