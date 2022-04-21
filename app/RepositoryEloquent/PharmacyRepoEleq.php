<?php

namespace App\RepositoryEloquent;


use App\Models\Pharmacy;
use App\Repository\PharmacyRepo;

class PharmacyRepoEleq implements PharmacyRepo
{
    public function store(array  $data)
    {
        return Pharmacy::create($data);
    }
    public function edit($id)
    {
        return Pharmacy::findOrFail($id);
    }
    public function update($id,array  $data)
    {
        $user= Pharmacy::findOrFail($id);
        return $user->update($data);
    }
    public function delete($id)
    {
        $user= Pharmacy::findOrFail($id);
        return $user->delete($id);

    }
    public function deletes($data)
    {
        $items = Pharmacy::whereIn('id', $data['data']);
        if (!$items->count()) {
            return redirect()->back()->with('danger', 'يجب اختيار عنصر علي الافل');
        }
        $items->delete();
    }
}
