<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\GeneralController;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Repositories\Contracts\ISettingRepository;

class SettingController extends GeneralController
{

    protected $viewPath = 'setting.';
    protected $path = 'setting';
    protected $quality = 100;
    protected $encode = 'png';


    public function __construct(ISettingRepository $model)
    {
        $this->model = $model;
    }


    /**
     * Get All Data Model
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->model->all();
        return view($this->viewPath($this->viewPath . 'edit'), compact('data'));
    }


    public function update(SettingRequest $request)
    {
        $data = $this->model->all();
        $inputs = $request->validated();
        if($request->hasFile('logo')) {
            $inputs['logo'] = $this->uploadImage($request->file('logo'), $this->path, $data->where('key', 'logo')->first()->val);
        }
        if($request->hasFile('logo_login')) {
            $inputs['logo_login'] = $this->uploadImage($request->file('logo_login'), $this->path, $data->where('key', 'logo_login')->first()->val);
        }
        if($request->hasFile('login_pg')) {
            $inputs['login_pg'] = $this->uploadImage($request->file('login_pg'), $this->path, $data->where('key', 'login_pg')->first()->val , null,900);
        }
        Setting::setMany($inputs);
        return redirect()->back()->with('success', 'تم التعديل بنجاح');

    }
}
