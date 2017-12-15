<?php
namespace App\Filters;

use App\User;
use App\Filters\Filter;

class ThreadsFilter extends Filter
{
    protected $filters = [ 'by', 'popular' ];
    
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}
