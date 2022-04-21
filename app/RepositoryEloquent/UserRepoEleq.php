<?php

namespace App\RepositoryEloquent;


use App\Models\User;
use App\Repository\UserRepo;

class UserRepoEleq implements UserRepo
{
    public function store(array  $data)
    {
        return User::create($data);
    }
    public function edit($id)
    {
        return User::findOrFail($id);
    }
    public function update($id,array  $data)
    {
        $user= User::findOrFail($id);
        return $user->update($data);
    }
    public function delete($id)
    {
        $user= User::findOrFail($id);
        return $user->delete($id);

    }
    public function deletes($data)
    {
        $items = User::whereIn('id', $data['data']);
        if (!$items->count()) {
            return redirect()->back()->with('danger', 'يجب اختيار عنصر علي الافل');
        }
        $items->delete();
    }
}
