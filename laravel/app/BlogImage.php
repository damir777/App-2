<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $connection = 'blog';

    protected  $table = 'system_files';
}
