<?php

namespace App\RepositoryEloquent;


use App\Models\Admin;
use App\Repository\AdminRepo;
use Spatie\Permission\Models\Role;

class AdminRepoEleq implements AdminRepo
{
    public function roles()
    {
        return Role::get();
    }
    public function store(array  $data)
    {
        return Admin::create($data);
    }
    public function edit($id)
    {
        return Admin::findOrFail($id);
    }
    public function update($id,array  $data)
    {
        $shift= Admin::findOrFail($id);
        return $shift->update($data);
    }
    public function delete($id)
    {
        $shift= Admin::findOrFail($id);
        return $shift->delete($id);

    }
    public function deletes($data)
    {
        $items = Admin::whereIn('id', $data['data']);
        $items->delete();

    }
}
