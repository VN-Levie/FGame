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
        $post_dependents = [['ForumCategory', 'category_id'], ['User']];
        $posts = Forum::whereWiths($post_dependents, sort_by: 'DESC');
        // var_dump($posts);
        $data = [
            'title' => 'Quản lý bài viết forum',
            // 'mother_threads' => $forumCategories,
            'posts' => $posts
        ];
        $this->view('dashboard.forum.index', $data);
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
        $user_id = $user?->id ?? null;
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

    //delete post
    public function postDelete()
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
        if ($id == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
        }
        $post = Forum::find($id);
        if (!$post) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
        }
        if ($post->soft_delete && !$user->checkRole("admin")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Chỉ admin mới có thể khôi phục bài viết'
            ]);
        }
        $post->soft_delete = abs($post->soft_delete - 1);
        $post->save();
        return $this->json([
            'status' => 'success',
            'message' => $post->soft_delete ? 'Xóa bài viết thành công' : 'Khôi phục bài viết thành công'
        ]);
    }

    //post hide
    public function postHide()
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
        if ($id == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
        }
        $post = Forum::find($id);
        if (!$post) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
        }
        $post->hide = abs($post->hide - 1);
        $post->save();
        return $this->json([
            'status' => 'success',
            'message' => ($post->hide ? 'Khóa' : 'Mở'). '  bình luận bài viết thành công'
        ]);
    }


    public function categories()
    {

        $dependents = [['User']];
        $form_categories = ForumCategory::whereWiths($dependents);
        // print_r($form_categories);
        $data = [
            'form_categories' => $form_categories
        ];
        $this->view('dashboard.forum.categories', $data);
    }

    //create category
    public function categoryForm()
    {
        $id = $_GET['id'] ?? null;
        // if($id == null){
        //     return View::abort(404, 'Danh mục không tồn tại');
        // }
        $category = ForumCategory::find($id) ?? null;
        $data = [
            'category' => $category
        ];
        $this->view('dashboard.forum.categoryForm', $data);
    }

    //categoryFormSubmit
    public function categoryFormSubmit()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        //check role 'mod'
        if (!$user->checkRole("admin")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Chỉ admin mới có thể tạo/sửa danh mục'
            ]);
        }
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $user_id = $user?->id ?? null;
        //check null name
        if (!$name) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập tên'
            ]);
        }
        //check null description
        if (!$description) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập mô tả'
            ]);
        }

        if ($id != null) {
            $category = ForumCategory::find($id);
            if (!$category) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại'
                ]);
            }
            $mess = 'Cập nhật thành công';
        } else {
            $category = new ForumCategory();
            $mess = 'Tạo danh mục thành công';
        }
        $category->name = $name;
        $category->description = $description;
        $category->user_id = $user_id;
        $category->save();
        return $this->json([
            'status' => 'success',
            'message' => $mess
        ]);

        // header('Location: ' . route('dashboard.forum'));
    }

    //delete category
    public function categoryDelete()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        //check role 'mod'
        if (!$user->checkRole("admin")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền'
            ]);
        }
        $id = $_POST['id'] ?? null;
        if ($id == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại'
            ]);
        }
        $category = ForumCategory::find($id);
        if (!$category) {
            return $this->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại'
            ]);
        }
        if ($category->soft_delete && !$user->checkRole("s-admin")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Chỉ s-admin mới có thể khôi phục danh mục'
            ]);
        }
        $category->soft_delete = abs($category->soft_delete - 1);
        $category->save();
        return $this->json([
            'status' => 'success',
            'message' =>  $category->soft_delete ? 'Xóa danh mục thành công' : 'Khôi phục danh mục thành công'
        ]);
    }

    //category hide
    public function categoryHide()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        //check role 'mod'
        if (!$user->checkRole("s-mod")) {
            return $this->json([
                'status' => 'error',
                'message' => 'Chỉ s-mod mới có thể mở/khóa danh mục'
            ]);
        }
        $id = $_POST['id'] ?? null;
        if ($id == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại'
            ]);
        }
        $category = ForumCategory::find($id);
        if (!$category) {
            return $this->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại'
            ]);
        }
        $category->hide = abs($category->hide - 1);
        $category->save();
        return $this->json([
            'status' => 'success',
            'message' => ($category->hide ? 'Khóa' : 'Mở'). '  đăng bài viết mới trong danh mục thành công'
        ]);
    }
}
