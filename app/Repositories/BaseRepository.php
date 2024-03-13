<?php

namespace App\Repositories;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class BaseRepository
{
    protected Model $model;

    protected int $limit = 20;

    protected string $sort = 'id:desc';

    protected Container $app;

    /**
     * @throws Exception
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->bindModel();
    }

    abstract public function model();

    /**
     * @throws Exception
     */
    public function bindModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function prepareQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function dataFilter($filters)
    {
        return $filters;
    }

    public function dataOrder($orders)
    {
        return $orders;
    }

    public function customData($data)
    {
        return $data;
    }

    /**
     * @throws Exception
     */
    public function resetModel(): void
    {
        $this->bindModel();
    }

    public function prepareData($attributes)
    {
        return $attributes;
    }

    /**
     * @throws Exception
     */
    public function getAll(array $params = [], $query = null)
    {
        if (!$query) {
            $query = $this->prepareQuery();
        }

        $filters = Arr::except($params, ['limit', 'sorts']);
        $limit = data_get($params, 'limit', $this->limit);
        $sorts = explode(',', data_get($params, 'sorts', $this->sort));
        $orders = [];

        foreach ($sorts as $sort) {
            $item = explode(':', $sort);

            // CHECK SORTS & ASSIGN VALUE
            if (count($item) >= 2) {
                $key = $item[0];
                $value = $item[1];

                $orders[$key] = $value;
                $query = $query->orderBy($key, $value);
            }
        }

        $filters = $this->dataFilter($filters); // Search conditional
        $orders = $this->dataOrder($orders); // Order by colum

        $result = $query->filter($filters)
            ->sort($orders)
            ->paginate($limit)
            ->appends($params);

        $result = $this->customData($result);
        $this->resetModel();

        return $result;
    }

    /**
     * Get all the models from the database.
     *
     * @param string[] $columns
     *
     * @return Collection|array
     *
     * @throws Exception
     */
    public function all(array $columns = ['*']): Collection|array
    {
        $query = $this->model->newQuery();
        $result = $query->get(
            is_array($columns) ? $columns : func_get_args()
        );

        $this->resetModel();
        return $result;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes = ['*']): Model
    {
        $attributes = $this->prepareData(
            is_array($attributes) ? $attributes : func_get_args()
        );
        $model = $this->model->newInstance($attributes);

        $model->save();
        return $model;
    }

    /**
     * @param $id
     * @param string[] $columns
     *
     * @return Model|Collection|Builder|array|null
     *
     * @throws Exception
     */
    public function find($id, array $columns = ['*']): Model|Collection|Builder|array|null
    {
        $query = $this->model->newQuery();
        $model = $query->find($id, $columns);
        $this->resetModel();

        return $model;
    }

    /**
     * @param $id
     * @param string[] $columns
     *
     * @return Builder|Builder[]|Collection|Model|null
     *
     * @throws Exception
     */
    public function findOrFail($id, array $columns = ['*']): Model|Collection|Builder|array|null
    {
        $query = $this->model->newQuery();
        $model = $query->findOrFail($id, $columns);

        $this->resetModel();
        return $model;
    }

    /**
     * @param array $attributes
     * @param $id
     *
     * @return Model|Collection|Builder|array|null
     */
    public function update(array $attributes, $id): Model|Collection|Builder|array|null
    {
        $attributes = $this->prepareData($attributes);
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    /**
     * @param $id
     *
     * @return bool|mixed|null
     */
    public function delete($id): mixed
    {
        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);

        return $model->delete();
    }

    /**
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->model->destroy($ids);
    }
}
