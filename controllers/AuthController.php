<?php

use Controllers\Controller;
use Models\User;

class AuthController extends Controller
{
    //login
    public function login()
    {
        $data = [
            'title' => 'Đăng nhập',
            'backgroud_img' => 'https://i.imgur.com/H5Ez361.jpg',
            'hide_navbar' => true
        ];
        $this->view('auth/login', $data);
    }

    public function doLogin()
    {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        //check null username 
        if (!$username) {
            return  $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập tài khoản'
            ]);
        }
        //check null password
        if (!$password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập mật khẩu'
            ]);
        }
        $user = User::getByUsername($username);
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Tài khoản không tồn tại'
            ]);
        }
        //check password
        if ($password != $user->password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Mật khẩu không đúng'
            ]);
        }

        $_SESSION['user'] = $user;

        $this->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công'
        ]);
    }

    //logout
    public function logout()
    {
        unset($_SESSION['user']);
        $this->redirect('/');
    }

    //register
    public function register()
    {
        $data = [
            'title' => 'Đăng ký',
            'backgroud_img' => 'https://i.imgur.com/H5Ez361.jpg',
            'hide_navbar' => true
        ];
        $this->view('auth/register', $data);
    }

    public function doRegister()
    {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $password_confirmation = $_POST['password_confirmation'] ?? null;
        //check null username
        if (!$username) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập tài khoản'
            ]);
        }
        //check null password
        if (!$password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập mật khẩu'
            ]);
        }
        //check password_confirmation
        if ($password != $password_confirmation) {
            return $this->json([
                'status' => 'error',
                'message' => 'Mật khẩu không khớp'
            ]);
        }
        //check trùng username
        $user = User::getByUsername($username);
        if ($user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Tài khoản đã tồn tại'
            ]);
        }

        
        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->email = '';
        $user->roles = 0;
        $user->save();

        $this->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công'
        ]);
    }

    //profile
    public function profile()
    {
        global $user;
        if (!$user) {
            $this->redirect('/login');
        }
        $data = [
            'title' => 'Hồ sơ',
        ];
        $this->view('auth/profile', $data);
    }

    //doChangePassword
    public function doChangePassword()
    {
        global $user;
        if (!$user) {
            $this->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ]);
        }
        $old_password = $_POST['old_password'] ?? null;
        $new_password = $_POST['new_password'] ?? null;
        $new_password_confirmation = $_POST['new_password_confirmation'] ?? null;
        //check null old_password
        if (!$old_password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập mật khẩu cũ'
            ]);
        }
        //check null new_password
        if (!$new_password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vui lòng nhập mật khẩu mới'
            ]);
        }
        //check new_password_confirmation
        if ($new_password != $new_password_confirmation) {
            return $this->json([
                'status' => 'error',
                'message' => 'Mật khẩu mới không khớp'
            ]);
        }
        //check old_password
        if ($old_password != $user->password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Mật khẩu cũ không đúng'
            ]);
        }
        $user->password = $new_password;
        $user->save();
        $this->json([
            'status' => 'success',
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
