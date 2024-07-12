<?php

use Controllers\Controller;

class ProductController extends Controller
{
    // Your controller code here
    
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
        $posts = Forum::whereWiths($post_dependents, $condition, sort_by: 'DESC');
        // var_dump($posts);
        $data = [
            'title' => 'Quản lý bài viết forum',
            // 'mother_threads' => $forumCategories,
            'posts' => $posts
        ];
        $this->view('dashboard.forum.index', $data);
    }
}