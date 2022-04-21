<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AdminDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\General\MultiDelete;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ProfileController extends GeneralController
{
    protected $viewPath = 'profile.';
    protected $path = 'profile';
    private $route = 'admin';

    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function edit()
    {
        // Get and Check Data
        $data = $this->model->findorfail(auth()->user()->id);
        // Get Roles
        return view($this->viewPath($this->viewPath . 'edit'), compact('data'));
    }


    public function update(ProfileRequest $request, $id)
    {
        // Get and Check Data
        $data = $this->model->findOrfail($id);

        // Get data from request
        $inputs = $request->validated();
        // Set Password if exist inputs data
        if (!empty($request->input('password'))) {
//            $inputs['password'] = bcrypt($request->input('password'));
        } else {
            unset($inputs['password']);
        }
        $updated = $data->update($inputs);

        return redirect()->route($this->route)->with('success', 'تم تعديل الملف الشخصي بنجاح');
    }
}
