<?php

use Controllers\Controller;
use Models\Forum;
use Models\ForumCategory;
use Models\ForumComment;
use Models\Game;
use Models\GameCategory;
use Models\Order;
use Models\Product;
use Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        $category_id = $_GET['id'] ?? null;
        if ($category_id != null) {
            $forums = Forum::findWhere(conditions: ['category_id' => $category_id]);
            //loại bỏ các forum soft_delete = 1, hide = 1 và archive_by_category = 1
            $forums = array_filter($forums, function ($forum) {
                return $forum->soft_delete == 0 && $forum->archive_by_category == 0;
            });
        } else {
            $forums = Forum::whereWiths(sort_by: 'desc');
            $forums = array_filter($forums, function ($forum) {
                return $forum->soft_delete == 0  && $forum->archive_by_category == 0;
            });
        }

        $forum_categories = ForumCategory::all();
        $games = Game::all();
        $data = [
            'forums' => $forums,
            'forum_categories' => $forum_categories,
            'games' => $games,
            'id' => $category_id
        ];
        View::render('home.index', $data);
    }

    //detail
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id == null) {
            header('Location: /');
            exit();
        }
        $forum = Forum::find($id);
        if ($forum == null) {
            header('Location: /');
            exit();
        }
        $forum->views += 1;
        $forum->save();
        $data = [
            'title' => $forum->title . ' - ' . $forum->getCategory()->name,
            'forum' => $forum
        ];
        View::render('home.forum.detail', $data);
    }

    //commentSubmit
    public function commentSubmit()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        $forum_id = $_POST['forum_id'] ?? null;
        $content = $_POST['content'] ?? null;
        if ($forum_id == null || $content == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ'
            ]);
        }
        $forum = Forum::find($forum_id);
        if ($forum == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
        }
        $forum->views += 1;
        $forum->save();
        $comment = new ForumComment();
        $comment->forum_id = $forum_id;
        $comment->user_id = $_SESSION['user']->id;
        $comment->content = $content;
        $comment->save();
        return $this->json([
            'status' => 'success',
            'message' => 'Đăng đăng tải bình luận'
        ]);
    }

    //postForm
    public function postForm()
    {
        global $user;
        if (!$user) {
            return View::abort(403, 'Vui lòng đăng nhập');
        }

        $id = $_GET['id'] ?? null;
        $form_categories = ForumCategory::all();
        $post = Forum::find($id) ?? null;
        $data = [
            'form_categories' => $form_categories,
            'post' => $post
        ];
        View::render('home.forum.post', $data);
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
    }

    //shop
    public function shop()
    {
        $category_id = $_GET['id'] ?? null;
        if ($category_id != null) {
            $products = Product::findWhere(conditions: ['category_id' => $category_id]);
        } else {
            $products = Product::all();
        }
        $product_categories = ProductCategory::all();
        $data = [
            'products' => $products,
            'product_categories' => $product_categories,
            'id' => $category_id
        ];
        View::render('home.shop.index', $data);
    }

    //shopDetail
    public function shopDetail()
    {
        $id = $_GET['id'] ?? null;
        if ($id == null) {
            header('Location: /shop');
            exit();
        }
        $product = Product::find($id);
        if ($product == null) {
            header('Location: /shop');
            exit();
        }
        $data = [
            'title' => $product->name,
            'product' => $product
        ];
        View::render('home.shop.detail', $data);
    }

    //buy
    public function buy()
    {
        global $user;
        if (!$user) {
            return View::abort(403, 'Vui lòng đăng nhập');
        }
        $id = $_GET['id'] ?? null;
        if ($id == null) {
           return View::abort(404, 'Sản phẩm không tồn tại');
        }
        $product = Product::find($id);
        if ($product == null) {
            return View::abort(404, 'Sản phẩm không tồn tại');
        }
        $data = [
            'title' => 'Mua sản phẩm',
            'product' => $product
        ];
        View::render('home.shop.buy', $data);
    }

    //buySubmit
    public function buySubmit()
    {
        global $user;
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        $id = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        //cc-name
        $cc_name = $_POST['cc-name'] ?? null;
        //cc-number
        $cc_number = $_POST['cc-number'] ?? null;
        //cc-expiration
        $cc_expiration = $_POST['cc-expiration'] ?? null;
        //cc-cvv
        $cc_cvv = $_POST['cc-cvv'] ?? null;
        //check null
        if ($cc_name == null || $cc_number == null || $cc_expiration == null || $cc_cvv == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập đủ thông tin thanh toán'
            ]);
        }
        if ($id == null || $quantity == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ'
            ]);
        }
        //check số lượng
        if ($quantity <= 0) {
            return $this->json([
                'status' => 'error',
                'message' => 'Số lượng không hợp lệ'
            ]);
        }
        $product = Product::find($id);
        if ($product == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại'
            ]);
        }
        //check số lượng tồn
        if ($product->stock < $quantity) {
            return $this->json([
                'status' => 'error',
                'message' => 'Sản phẩm không đủ số lượng'
            ]);
        }
        $total = $product->price * $quantity;
        $order = new Order();
        $order->product_id = $id;
        $order->user_id = $user->id;
        $order->quantity = $quantity;
        $order->total = $total;
        $order->status = 'success';
        $order->product_price = $product->price;
        $order->save();
        $product->stock -= $quantity;
        $product->save();
        return $this->json([
            'status' => 'success',
            'message' => 'Đặt hàng thành công'
        ]);
    }
}
