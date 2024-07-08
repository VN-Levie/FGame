<?php

use Controllers\Controller;
use Models\Forum;
use Models\ForumCategory;
use Models\Game;
use Models\GameCategory;

class ForumController extends Controller
{
    public function index()
    {
        $post_dependents = [['ForumCategory', 'forum_categories', 'category_id', 'category'], ['User', 'users']];
        $posts = Forum::whereWiths(['Forum', 'forum'], $post_dependents, sort_by: 'DESC');
        // var_dump($posts);
        $data = [
            'title' => 'Quản lý bài viết forum',
            // 'mother_threads' => $forumCategories,
            'posts' => $posts
        ];
        $this->view('dashboard.forum.index', $data);
    }

    public function categories()
    {
        $game_categories = GameCategory::all();
        $data = [
            'game_categories' => $game_categories
        ];
        $this->view('dashboard.forum.categories', $data);
    }

    //create category
    public function createCategory()
    {
        $game_categories = GameCategory::all();
        $data = [
            'game_categories' => $game_categories
        ];
        $this->view('dashboard.forum.createCategory', $data);
    }
}
