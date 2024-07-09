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
        $form_categories = ForumCategory::all();
        $data = [
            'game_categories' => $form_categories
        ];
        $this->view('dashboard.forum.categories', $data);
    }

    //create category
    public function createCategory()
    {
        $form_categories = ForumCategory::all();
        $data = [
            'game_categories' => $form_categories
        ];
        $this->view('dashboard.forum.createCategory', $data);
    }

    //form post
    public function postForm()
    {
        $id = $_GET['id'] ?? null;
        $form_categories = ForumCategory::all();
        $post = Forum::find($id) ?? null;
        $data = [
            'form_categories' => $form_categories,
            'post' => $post
        ];
        $this->view('dashboard.forum.postForm', $data);
    }

    //postFormSubmit 
    public function postFormSubmit()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        //check role 'mod'
        if (!$user->checkRole("mod")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền'
            ]);
        }
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? null;
        $content = $_POST['content'] ?? null;
        $category = $_POST['category'] ?? null;
        $user_id = $_SESSION['user']?->id ?? null;
        //check null title
        if (!$title) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập tiêu đề'
            ]);
        }
        //check null content
        if (!$content) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập nội dung'
            ]);
        }
        //check null category
        if (!$category) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng chọn danh mục'
            ]);
        }

        if ($id != null) {
            $post = Forum::find($id);
            if (!$post) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại'
                ]);
            }
            $mess = 'Cập nhật thành công';
        } else {
            $post = new Forum();
            $post->views = 0;
            $post->user_id = $user_id;
            $mess = 'Tạo bài viết thành công';
        }
        $post->title = $title;
        $post->content = $content;
        $post->category_id = $category;
        $post->save();
        return $this->json([
            'status' => 'success',
            'message' => $mess
        ]);

        // header('Location: ' . route('dashboard.forum'));
    }
}
