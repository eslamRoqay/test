<?php

namespace App\Repositories\SQL;

use App\Models\Setting;
use App\Models\User;
use App\Repositories\Contracts\ISettingRepository;
use App\Repositories\Contracts\IUserRepository;
use Carbon\Carbon;

class SettingRepository extends AbstractModelRepository implements ISettingRepository
{

    public $model;
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }


}
