<?php

use Controllers\Controller;
use Models\Game;
use Models\GameCategory;

class GameController extends Controller
{
    public function index()
    {
        global $user;
        if (!$user) {
            return View::abort(403, 'Vui lòng đăng nhập');
        }
        //check role 
        if (!$user->checkRole("mod")) {
            return View::abort(403, 'Bạn không có quyền truy cập trang này');
        }
        $category = $_GET['category'] ?? null;
        $post_dependents = [['ForumCategory', 'category_id'], ['User']];
        $condition = [];
        if ($category != null) {
            $condition = ['category_id' => $category];
        }
        $games = Game::whereWiths(sort_by: 'DESC');
        // var_dump($posts);
        $data = [
            'title' => 'Quản lý bài viết forum',
            // 'mother_threads' => $forumCategories,
            'games' => $games
        ];
        $this->view('dashboard.game.index', $data);
    }
}