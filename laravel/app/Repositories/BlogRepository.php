<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\BlogPost;
use App\BlogImage;

class BlogRepository
{
    //get blog posts
    public function getBlogPosts() /////////////////////////////////////
    {
        try
        {
            //set default logged user variable
            $logged_user = 'false';

            //if user is logged in set logged user to 'true'
            if (Auth::user())
            {
                $logged_user = 'true';
            }

            //set destination posts array
            $destination_posts_array = [];

            //set home posts array
            $home_posts_array = [];

            $destination_posts = BlogPost::select('id', 'title', 'slug', 'featured', 'pinned')
                ->whereIn('id', function($query) {
                    $query->select('post_id')
                        ->from('rainlab_blog_posts_categories')
                        ->where('category_id', '=', 9);
                })
                ->orderBy('pinned', 'desc')->orderBy('id', 'desc')->get();

            $posts_counter = 1;

            foreach ($destination_posts as $post)
            {
                $image = BlogImage::select('disk_name')->where('attachment_id', '=', $post->id)->first();

                $folder = substr($image->disk_name, 0, 3);
                $folder .= '/'.substr($image->disk_name, 3, 3);
                $folder .= '/'.substr($image->disk_name, 6, 3);

                $post->image = 'http://blog.xx.com/storage/app/uploads/public/'.$folder.'/'.
                    $image->disk_name;

                $post->link = 'http://blog.xx.com/destinations/'.$post->slug.'?logged='.$logged_user;
            }

            $home_posts = BlogPost::select('id', 'title', 'slug', 'featured', 'pinned')
                ->whereIn('id', function($query) {
                    $query->select('post_id')
                        ->from('rainlab_blog_posts_categories')
                        ->where('category_id', '=', 12);
                })
                ->orderBy('pinned', 'desc')->orderBy('id', 'desc')->get();

            $posts_counter = 1;

            foreach ($home_posts as $post)
            {
                $image = BlogImage::select('disk_name')->where('attachment_id', '=', $post->id)->first();

                $folder = substr($image->disk_name, 0, 3);
                $folder .= '/'.substr($image->disk_name, 3, 3);
                $folder .= '/'.substr($image->disk_name, 6, 3);

                $post->image = 'http://blog.xx.com/storage/app/uploads/public/'.$folder.'/'.
                    $image->disk_name;

                $post->link = 'http://blog.xx.com/home/'.$post->slug.'?logged='.$logged_user;

                if ($post->featured)
                {
                    if (!array_key_exists(0, $home_posts_array))
                    {
                        $home_posts_array[0] = $post;
                    }
                    elseif (!array_key_exists(5, $home_posts_array))
                    {
                        $home_posts_array[5] = $post;
                    }
                }
                else
                {
                    while (array_key_exists($posts_counter, $home_posts_array))
                    {
                        $posts_counter++;
                    }

                    $home_posts_array[$posts_counter] = $post;
                }

                if (count($home_posts_array) == 6)
                {
                    break;
                }
            }

            return ['status' => 1, 'destination_posts' => $destination_posts, 'home_posts' => $home_posts_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}