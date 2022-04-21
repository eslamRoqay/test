<?php

namespace App\Repositories\SQL;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Carbon\Carbon;

class UserRepository extends AbstractModelRepository implements IUserRepository
{

    public $model;
    public function __construct(User $model)
    {
        $this->model = $model;
    }


}
