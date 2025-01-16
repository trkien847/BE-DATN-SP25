<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\CategoryTypeRepository;
use App\Models\CategoryType;
use App\Validators\CategoryTypeValidator;

/**
 * Class CategoryTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class CategoryTypeRepositoryEloquent extends BaseRepository implements CategoryTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CategoryType::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
