<?php

namespace App\Repositories\SQL;

use App\Models\Admin;
use App\Repositories\Contracts\IAdminRepository;

class AdminRepository extends AbstractModelRepository implements IAdminRepository
{

    public $model;
    public function __construct(Admin $model)
    {
        $this->model = $model;
    }


}
