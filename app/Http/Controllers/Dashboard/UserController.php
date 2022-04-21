<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\UserDataTable;
use App\DataTables\UserShiftsDataTable;
use App\Http\Controllers\GeneralController;
use App\Http\Requests\General\MultiDelete;
use App\Http\Requests\UserRequest;
use App\RepositoryEloquent\AdminRepoEleq;
use App\RepositoryEloquent\UserRepoEleq;
use Illuminate\Http\Request;

class UserController extends GeneralController
{
    protected $viewPath = 'user.';
    protected $path = 'user';
    private $route = 'users';
    private $image_path = 'users';
    protected  $model;


    public function __construct(UserRepoEleq $model)
    {
        $this->model=$model;
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('dashboard.' . $this->viewPath . '.index');
    }

    public function indexShifts(UserShiftsDataTable $dataTable,$id)
    {
        return $dataTable->with('key',$id)->render('dashboard.' . $this->viewPath . '.shifts');
    }

    public function create()
    {
        return view('dashboard.' . $this->viewPath . '.create');
    }

    public function store(UserRequest $request)
    {
        $this->model->store($request->validated());
        return redirect()->route($this->route)->with('success','تم الاضافه بنجاح');
    }

    public function edit($id)
    {
        $data = $this->model->edit($id);
        return view('dashboard.' . $this->viewPath . '.edit', compact('data'));
    }

    public function update(UserRequest $request,$id)
    {
        $data = $request->validated();
        if ($request->password == null)
        {
            unset($data['password']);
        }
        $data = $this->model->update($id,$data);
        return redirect()->route($this->route)->with('success','تم التعديل بنجاح');
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = $this->model->delete($id);
            return redirect()->back()->with('success','تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'لا يمكنك الحذف لانه مستخدم في دوام');
        }
    }
    public function deletes(MultiDelete $request)
    {
        try {
            $data = $request->validated();
            $this->model->deletes($data);
            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'لا يمكنك الحذف لانه مستخدم في دوام');
        }
    }


}
