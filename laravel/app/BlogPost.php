<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $connection = 'blog';

    protected $table = 'rainlab_blog_posts';
}
