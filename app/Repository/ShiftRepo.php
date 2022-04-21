<?php

namespace App\Repository;

Interface ShiftRepo{

   public function store(array  $data);
   public function edit($id);
   public function update($id,array  $data);
   public function delete($id);

}
