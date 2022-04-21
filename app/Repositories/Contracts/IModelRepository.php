<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IModelRepository
{
    const LIMIT = 10;
    const ORDER_BY = 'created_at';
    const ORDER_DIR = 'desc';

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return mixed
     */
    public function update(Model $model, $attributes = []);

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function updateAll($attributes = []);

    /**
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function createOrUpdate($attributes = [], $id = null);
    /*
     * @param array $searchIn
     * @param array $attributes
     *
     * @return mixed
     */
    public function updateOrCreate(array $searchIn = [], array $attributes = []);

    /**
     * @param Model $model
     *
     * @return mixed
     */
    public function remove(Model $model);

    /**
     * @param int $id
     * @param array $relations
     *
     * @return mixed
     */
    public function find(int $id, array $relations = []);

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */

    public function wherein($key,array $value);

    public function findBy($key, $value);

    /**
     * @param mixed $fields
     *
     * @return mixed
     */
    public function findByFields(array $fields);

    /**
     * @param array $fields
     * @return mixed
     */
    public function findByFieldsAll(array $fields);

    /**
     * @param array $wheres
     * @param array|null $data
     * @return mixed
     */
    public function WhereOrCreate(array $wheres, array $data = null);

    /**
     * @param string $labelField
     * @param string $valueField
     *
     * @return mixed
     */
    public function findAllForFormSelect(
        $labelField = null,
        $valueField = 'id',
        $applyOrder = false,
        $orderBy = self::ORDER_BY,
        $orderDir = self::ORDER_DIR,
        $where    = []
    );

    /**
     * @param boolean $applyOrder
     * @param string $orderBy
     * @param string $orderDir
     *
     * @return mixed
     */
    public function findAll($fields = [], $applyOrder = true, $orderBy = self::ORDER_BY, $orderDir = self::ORDER_DIR);

    /**
     * @param array $filters
     * @param array $relations
     * @param bool|false $page
     * @param int $limit
     * @param string $orderBy
     * @param string $orderDir
     *
     * @return mixed
     */
    public function search(
        $filters = [],
        $relations = [],
        $applyOrder = true,
        $page = true,
        $limit = self::LIMIT,
        $orderBy = self::ORDER_BY,
        $orderDir = self::ORDER_DIR,
        $wheres =[],
        $whereIn = []
    );

    /**
     * @param            $query
     * @param bool|false $page
     * @param int $limit
     * @param string $orderBy
     * @param string $orderDir
     *
     * @return mixed
     */
    public function getQueryResult(
        $query,
        $applyOrder = true,
        $page = true,
        $limit = self::LIMIT,
        $orderBy = self::ORDER_BY,
        $orderDir = self::ORDER_DIR
    );

    /**
     * @param array $filers
     * @return mixed
     */
    public function searchByFilters($filters = [], $relations = []);

    public function all();

    public function first();

    #------------------------- additional functions (Nourhan) for repository----------------------------#

    public function getLastIndex();

    public function CreateMulti(array $data);

    public function updateById(array $data, $id);

    public function findByWithRelation($key, $value, array $relation);

    public function whereHas($relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc']);

    public function getWhere($data, $orderBy = ['column' => 'id', 'dir' => 'DESC']);

    public function limitWhere($data, $limit, $orderBy = ['column' => 'id', 'dir' => 'DESC']);

    public function getWhereDate($column, $date, $data = [],  $orderBy = ['column' => 'id', 'dir' => 'DESC']);

    public function getWith(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC']);

    public function paginateWhereWith(array $data, array $with, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10);

    public function paginateWhere(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10);

    public function paginate($limit = 10, $orderBy = ['column' => 'id', 'dir' => 'DESC']);

    public function delete($id);

    public function paginateWhereHasWith(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc'], $limit = 10);

    public function whereHasWith(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc']);

    public function getWhereWith(array $with, array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC']);
}
