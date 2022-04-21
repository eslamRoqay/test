<?php

namespace App\Repository;

Interface UserRepo{

   public function store(array  $data);
   public function edit($id);
   public function update($id,array  $data);
   public function delete($id);
   public function deletes($data);

}
