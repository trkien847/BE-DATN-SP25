<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\CategoryRepository;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind interface với implementation
        $this->app->bind(CategoryRepository::class, CategoryRepositoryEloquent::class);
    }

    public function boot()
    {
        // Nếu cần, bạn có thể thêm logic khác ở đây
    }
}
