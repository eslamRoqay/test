<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\PharmacyDataTable;
use App\DataTables\PharmacyShiftsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Requests\General\MultiDelete;
use App\Http\Requests\PharmacyRequest;
use App\RepositoryEloquent\PharmacyRepoEleq;
use Illuminate\Http\Request;

class PharmacyController extends GeneralController
{
    protected $viewPath = 'pharmacy.';
    protected $path = 'pharmacy';
    private $route = 'pharmacies';
    protected $paginate = 30;
    private $image_path = 'pharmacies';


    public function __construct(PharmacyRepoEleq $model)
    {
        $this->model=$model;
    }

    public function index(PharmacyDataTable $dataTable)
    {
        return $dataTable->render('dashboard.' . $this->viewPath . '.index');
    }


    public function indexShifts(PharmacyShiftsDataTable $dataTable,$id)
    {

        return $dataTable->with('key',$id)->render('dashboard.' . $this->viewPath . '.shifts');
    }

    public function create()
    {
        return view('dashboard.' . $this->viewPath . '.create');
    }

    public function store(PharmacyRequest  $request)
    {
        $this->model->store($request->validated());
        return redirect()->route($this->route)->with('success','تم الاضافه بنجاح');
    }

    public function edit($id)
    {
        $data = $this->model->edit($id);
        return view('dashboard.' . $this->viewPath . '.edit', compact('data'));
    }

    public function update(PharmacyRequest  $request,$id)
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
            return redirect()->back()->with('danger', 'لا يمكنك الحذف لانه الصبدليه في دوام');
        }
    }


}
