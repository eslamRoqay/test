<?php

namespace App\Repositories\SQL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\IModelRepository;

abstract class AbstractModelRepository implements IModelRepository
{


    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create($attributes = [])
    {
        if (!empty($attributes)) {
            // Clean the attributes from unnecessary inputs
            $filterd = $this->cleanUpAttributes($attributes);

            return $this->model->create($filterd);
        }
        return false;
    }

    public function update(Model $model, $attributes = [])
    {
        if (!empty($attributes)) {
            // Clean the attributes from unnecessary inputs
            $filterd = $this->cleanUpAttributes($attributes);

            return tap($model)->update($filterd)->fresh();
        }
        return false;
    }

    public function updateAll($attributes = [])
    {
        if (!empty($attributes)) {
            // Clean the attributes from unnecessary inputs
            $filterd = $this->cleanUpAttributes($attributes);

            return $this->model->query()->update($filterd);
        }
        return false;
    }

    public function createOrUpdate($attributes = [], $id = null)
    {
        if (empty($attributes)) {
            return false;
        }

        // Clean the attributes from unnecessary inputs
        $filterd = $this->cleanUpAttributes($attributes);

        if ($id) {
            $model = $this->model->find($id);
            return $this->update($model, $filterd);
        }
        return $this->create($filterd);
    }

    public function updateOrCreate(array $searchIn = [], array $attributes = [])
    {
        $model = $this->model->updateOrCreate($searchIn, $attributes);


        return $model;
    }

    /**
     * @param Model $model
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function remove(Model $model)
    {
        return $model->delete();
    }

    public function count()
    {
        $query = $this->model;

        return $query->count();
    }

    /**
     * @param int $id
     * @param array $relations
     *
     * @return mixed
     */
    public function find(int $id, array $relations = [])
    {
        $query = $this->model;
        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function findBy($key, $value)
    {
        return $this->model->where($key, $value)->first();
    }

    public function wherein($key,array $value)
    {
        return $this->model->whereIn($key, $value);
    }

    public function findOne(string $value, string $column = 'id', $columns = ['*']){

        return $this->model()->where($column, $value)->firstOrFail($columns);
    }

    /**
     * @param mixed $fields
     *
     * @return mixed
     */
    public function findByFields(array $fields)
    {
        $query = $this->model;
        if (isset($fields['or'])) {
            $query = $query->orWhere(function (Builder $query) use ($fields) {
                foreach ($fields['or'] as $condition) {
                    $query = $query->orWhere($condition[0], $condition[1]);
                }
            });
        }
        if (isset($fields['and'])) {
            $query = $query->where($fields['and']);
        }

        /* foreach ($fields as $key => $value) {
            $query = $query->where($key, $value);
        } */

        return $query->first();
    }
    public function findByFieldsAll(array $fields)
    {
        $query = $this->model;

        if (isset($fields['and'])) {
            $query = $query->where($fields['and']);
        }

        if (isset($fields['or'])) {
            $query = $query->orWhere(function (Builder $query) use ($fields) {
                foreach ($fields['or'] as $condition) {
                    $query = $query->orWhere($condition[0], $condition[1]);
                }
            });
        }

        /* foreach ($fields as $key => $value) {
            $query = $query->where($key, $value);
        } */

        return $query->get();
    }


    public function WhereOrCreate(array $values, array $attributes = null)
    {
        $query = $this->model;
        return $query->firstOrCreate($attributes ?? $values, $values);

    }
    /**
     * @param string $labelField
     * @param string $valueField
     *
     * @return mixed
     */
    public function findAllForFormSelect($labelField = null, $valueField = 'id', $applyOrder = false, $orderBy = self::ORDER_BY, $orderDir = self::ORDER_DIR, $where=[])
    {
        $query = $this->model;
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        if(!empty($where)){
            $query = $query->where($where);
        }
        return $query->get()->pluck($valueField, $labelField);
    }

    /**
     * @param boolean $applyOrder
     * @param string $orderBy
     * @param string $orderDir
     *
     * @return mixed
     */
    public function findAll($fields = ['*'], $applyOrder = true, $orderBy = self::ORDER_BY, $orderDir = self::ORDER_DIR)
    {
        $query = $this->model;
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        return $query->get($fields);
    }

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
        $wheres    = [],
        $whereIn = []
    )
    {
        $query = $this->model;
        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        if (!empty($wheres)) {
            $query = $query->where($wheres);
        }if (!empty($whereIn)) {
            $query = $query->whereIn($whereIn['col'], $whereIn['ids']);
        }

        if (!empty($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                //if (isset($filters[$filter]) and !empty($filters[$filter])) {

                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        return $this->getQueryResult($query, $applyOrder, $page, $limit, $orderBy, $orderDir);
    }

    /**
     * @param            $query
     * @param bool|false $page
     * @param int $limit
     * @param string $orderBy
     * @param string $orderDir
     *
     * @return mixed
     */
    public function getQueryResult($query, $applyOrder = true, $page = true, $limit = self::LIMIT, $orderBy = self::ORDER_BY, $orderDir = self::ORDER_DIR)
    {
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }

        if (config('app.query_debug')) {
            return $query->toSql();
        }

        if ($page) {
            return $query->paginate($limit);
        }
        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }

    protected function cleanUpAttributes($attributes)
    {
        return $attributes;
        return collect($attributes)->filter(function ($value, $key) {
            if(($key != '_token'&& $key != '_method')){
                return $key;
            }
        })->toArray();

        return collect($attributes)->filter(function ($value, $key) {
            return $this->model->isFillable($key);
        })->toArray();
    }

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
    public function searchBySelected(
        $groupBy = null,
        $fields = [],
        $filters = [],
        $relations = [],
        $applyOrder = false,
        $page = false,
        $limit = false,
        $orderBy = self::ORDER_BY,
        $orderDir = self::ORDER_DIR

    )
    {
        $query = $this->model;

        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        if (!empty($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                //if (isset($filters[$filter]) and !empty($filters[$filter])) {
                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        if (!empty($fields)) {
            $query = $query->selectRaw(implode(',', $fields));
        }
        if (!empty($groupBy)) {
            $query = $query->groupBy(implode(',', $groupBy));
        }
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }

        if ($page) {
            return $query->paginate($limit);
        }
        if ($limit) {
            return $query->take($limit)->get();
        }
        return $query->get();
    }

    public function searchByFilters($filters = [], $relations = []){
        $query = $this->model;
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        if (!empty($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        return $this->getQueryResult($query);
    }


    public function all()
    {
        return $this->model->all();
    }

    public function first()
    {
        return $this->model->first();
    }

    #------------------------- additionl functions ----------------------------#

    public function getLastIndex(){

        $item = $this->model->orderBy('id', 'DESC')->first();

        if($item){

            return ($item->id + 1);
        }
        else{

            return 1;
        }
    }

     /**
     * insert more than 1 model to database
     * @params $data => array of columns names and its values
     */
    public function CreateMulti(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * updating a model in the database
     * @params $id => row id && $data => array of columns names and its values
     *
     */
    public function updateById(array $data, $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param $key
     * @param $value
     * @param $relation
     *
     * @return mixed
     */
    public function findByWithRelation($key, $value, array $relation)
    {
        return $this->model->with($relation)->where($key, $value)->first();
    }

    /**
     * retrieve all the row for the given model with a conditional relation
     * @param $relation
     * @param $data
     * @param $data2
     * @param OPTIONAL $orderBy with the column name && dir
     */
    public function whereHas($relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc'])
    {
        return $this->model->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }


    /**
     * retrieve all the rows matching the where condition
     * @param OPTIONAL $orderBy with the column name && dir
     */
    public function getWhere($data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }


    public function limitWhere($data, $limit, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->limit($limit)->get();
    }


    public function getWhereDate($column, $date, $data = [],  $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->whereDate($column, '=', $date)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve all the row for the given model with the given relations
     * @param OPTIONAL $orderBy with the column name && dir
     * @param $data =>array of the relational models to be retrieved
     * @param $limit => count of rows per page
     */
    public function getWith(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve the model paginated with th given relations and conditions
     * @param OPTIONAL $orderBy with the column name && dir
     * @param $data =>array of the relational models to be retrieved
     */
    public function paginateWhereWith(array $data, array $with, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10)
    {
        return $this->model->with($with)->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }

    public function paginateWhere(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10)
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }

    /**
     * retrieve the model paginated
     * @params OPTIONAL $orderBy with the column name && dir
     * @params $limit => count of rows per page
     */
    public function paginate($limit = 10, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }

    /**
     * delete row for the given model
     * @params $id => the row id to be deleted
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function paginateWhereHasWith(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc'], $limit = 10)
    {
        return $this->model->with($with)->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }


    /**
     * retrieve all the row for the given model with relation and a conditional relation
     * @param OPTIONAL $orderBy with the column name && dir
     */
    public function whereHasWith(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'Asc'])
    {
        return $this->model->with($with)->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve 1 row for the given model with the given relations
     * @params $id => the row id to be retrieved
     * @params $data =>array of the relational models to be retrieved
     */
    public function getWhereWith(array $with, array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($with)->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }




}
