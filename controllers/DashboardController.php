<?php

use Controllers\Controller;
use Models\Forum;
use Models\ForumComment;
use Models\Game;
use Models\GameCategory;
use Models\Order;
use Models\Traffic;
use Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        global $user;
        if (!$user) {
            return View::abort(403, 'Vui lòng đăng nhập');
        }
        //check role 
        if (!$user->checkRole("seller")) {
            return View::abort(403, 'Bạn không có quyền truy cập trang này');
        }
        $data = [
            'title' => 'Dashboard',
        ];
        if ($user->checkRole("mod")) {
            $forums = Forum::count();
            $comments = ForumComment::count();
            $game_categories = GameCategory::count();
            $games = Game::count();
            $users = User::count();
            $last_user = User::last();
            $sum_total = Order::sum('total');
            $traffics = Traffic::sum('count_up');
            $traffics_unique = Traffic::count();
            $oders = Order::whereWiths([['Product'], ['User']], sort_by: 'desc', limit: 10);
            // print_r($oders);
            $data = [
                'forums' => $forums,
                'game_categories' => $game_categories,
                'game' => $games,
                'users' => $users,
                'last_user' => $last_user,
                'oders' => $oders,
                'sum_total' => $sum_total,
                'comments' => $comments,
                'traffics' => $traffics,
                'traffics_unique' => $traffics_unique,
            ];
        }
        $my_products = $user->products() ?? [];
        $sum_my_total = $user->sumTotal() ?? 0;
        $my_orders = $user->orders() ?? [];
        $data = array_merge($data, [
            'my_products' => $my_products,
            'sum_my_total' => $sum_my_total,
            'my_orders' => $my_orders,
        ]);
        // print_r($data);
        $this->view('dashboard.index', $data);
    }


    //users
    public function users()
    {
        global $user;
        if (!$user) {
            return View::abort(403, 'Vui lòng đăng nhập');
        }
        //check role 
        if (!$user->checkRole("mod")) {
            return View::abort(403, 'Bạn không có quyền truy cập trang này');
        }
        $users = User::all();
        $data = [
            'title' => 'Quản lý người dùng',
            'users' => $users,
        ];
        $this->view('dashboard.user.index', $data);
    }

    //userDelete
    public function userDelete()
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
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
        $selectedUser = User::find($id);
        if (!$selectedUser) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
        if ($user->id == $selectedUser->id) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể xóa chính mình'
            ]);
        }
        if ($user->role <= $selectedUser->role) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể xóa người dùng cùng cấp hoặc cao hơn'
            ]);
        }

        $selectedUser->soft_delete = abs($selectedUser->soft_delete - 1);
        $selectedUser->save();
        return $this->json([
            'status' => 'success',
            'message' => $selectedUser->soft_delete ? 'Xóa thành công' : 'Khôi phục thành công'
        ]);
    }

    //userBan
    public function userBan()
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
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
        $selectedUser = User::find($id);
        if (!$selectedUser) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
        if ($user->id == $selectedUser->id) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể khóa chính mình'
            ]);
        }
        if ($user->role <= $selectedUser->role) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể khóa người dùng cùng cấp hoặc cao hơn'
            ]);
        }
        $selectedUser->baned = abs($selectedUser->baned - 1);
        $selectedUser->save();
        return $this->json([
            'status' => 'success',
            'message' => $selectedUser->baned ? 'Khóa thành công' : 'Mở khóa thành công'
        ]);
    }

    //userChangeRole
    public function userChangeRole()
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
        $role = $_POST['role'] ?? null;
        if ($id == null || $role == null) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng hoặc quyền đã chọn không hợp lệ'
            ]);
        }
        //role < 0 || role > 9
        if ($role < 0 || $role > 9) {
            return $this->json([
                'status' => 'error',
                'message' => 'Quyền không hợp lệ'
            ]);
        }
        //check current role and new role
        if ($user->role <= $role) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể thay đổi quyền thành quyền cùng cấp hoặc cao hơn'
            ]);
        }
        $selectedUser = User::find($id);
        if (!$selectedUser) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
        //check baned
        if ($selectedUser->baned == 1) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể thay đổi quyền của người dùng đã bị khóa'
            ]);
        }
        //check soft_delete
        if ($selectedUser->soft_delete == 1) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể thay đổi quyền của người dùng đã bị xóa'
            ]);
        }
        if ($user->id == $selectedUser->id) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể thay đổi quyền của chính mình'
            ]);
        }
        if ($user->role <= $selectedUser->role) {
            return $this->json([
                'status' => 'error',
                'message' => 'Không thể thay đổi quyền của người dùng cùng cấp hoặc cao hơn'
            ]);
        }
        $selectedUser->role = $role;
        $selectedUser->save();
        return $this->json([
            'status' => 'success',
            'message' => 'Thay đổi quyền thành công'
        ]);
    }
}
