<?php

namespace App\Repositories\SQL;

use App\Repositories\Contracts\IAdminRepository;
use Spatie\Permission\Models\Permission;


class PermissionRepository extends AbstractModelRepository implements IAdminRepository
{

    public $model;
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }


}
