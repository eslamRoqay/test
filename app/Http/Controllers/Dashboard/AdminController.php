<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AdminDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\General\MultiDelete;
use App\Models\Admin;
use App\RepositoryEloquent\AdminRepoEleq;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends GeneralController
{
    protected $viewPath = 'admin.';
    protected $path = 'admins';
    private $route = 'admins';


    public function __construct(AdminRepoEleq $model)
    {
        $this->model = $model;
    }


    public function index(AdminDataTable $dataTable)
    {
        return $dataTable->render('dashboard.admin.index');
    }

    private function roles()
    {
        return $this->model->roles();
    }

    /**
     * View Page Add New Data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Get Roles
        $roles = $this->roles();
        return view($this->viewPath($this->viewPath . 'create'), compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $inputs = $request->validated();
        DB::beginTransaction();
        $admin = $this->model->store($inputs);
        $admin->assignRole($request->input('role_id'));
        DB::commit();
        return redirect()->route($this->route)->with('success', 'تم الاضافه بنجاح');

    }

    public function edit($id)
    {
        $data = $this->model->edit($id);
        $roles = $this->roles();
        return view($this->viewPath($this->viewPath . 'edit'), compact('data', 'roles'));
    }


    public function update(AdminRequest $request, $id)
    {
        $inputs = $request->validated();
        if (!empty($request->input('password'))) {

        } else {
            unset($inputs['password']);
        }
        DB::beginTransaction();
        $this->model->update($id, $inputs);
        DB::table('model_has_roles')->where('model_id', $id)->update(['role_id' => $request['role_id']]);
        DB::commit();
        return redirect()->route($this->route)->with('success', 'تم الاضافه بنجاح');

    }


    public function delete($id)
    {

        if (($id == '1')) {
            return redirect()->route($this->route)->with('danger', 'لا يمكن حذف مدير الموقع');
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        // Delete Data from DB
        $this->model->delete($id);
        return redirect()->route($this->route)->with('success', 'تم الحذف بنجاح');

    }

    public function deletes(MultiDelete $request)
    {

        try {
            $data = $request->validated();
            $items = Admin::whereIn('id', $data['data']);
            if (!$items->count()) {
                return redirect()->back()->with('danger', 'يجب اختيار عنصر علي الافل');
            }
            if ((in_array(1, $data['data']))) {
                return redirect()->back()->with('danger', 'لا يمكنك حذف الادمن');
            }
            $this->model->deletes($data);
            return redirect()->back()->with('success', 'تم الحذف بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'لا يمكنك الحذف');
        }
    }


}
