<?php

namespace App\RepositoryEloquent;


use App\Models\Shift;
use App\Repository\ShiftRepo;
use App\Repository\UserRepo;

class ShiftRepoEleq implements ShiftRepo
{
    public function store(array  $data)
    {
        return Shift::create($data);
    }
    public function edit($id)
    {
        return Shift::findOrFail($id);
    }
    public function update($id,array  $data)
    {
        $shift= Shift::findOrFail($id);
        return $shift->update($data);
    }
    public function delete($id)
    {
        $shift= Shift::findOrFail($id);
        return $shift->delete($id);

    }
}
