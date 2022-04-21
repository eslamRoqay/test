<?php

namespace App\Repository;

Interface PharmacyRepo{

   public function store(array  $data);
   public function edit($id);
   public function update($id,array  $data);
   public function delete($id);
    public function deletes($data);


}
