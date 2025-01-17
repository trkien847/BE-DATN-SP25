<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\CategoryRepository;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;
use App\Repositories\Eloquent\CategoryTypeRepositoryEloquent;
use App\Repositories\Interfaces\CategoryTypeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind interface với implementation
        $this->app->bind(CategoryRepository::class, CategoryRepositoryEloquent::class);
        $this->app->bind(CategoryTypeRepository::class, CategoryTypeRepositoryEloquent::class);
    }

    public function boot()
    {
        // Nếu cần, bạn có thể thêm logic khác ở đây
    }
}
