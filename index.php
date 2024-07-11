<?php
ini_set('display_errors', 1);

//định nghĩa ROOT_PATH
define('ROOT_PATH', __DIR__);
//định nghĩa CACHE_VIEW
define('CAHCE_VIEW', false);
// upload path
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
//domain
define('DOMAIN', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);


//thực hiện autoload các file cần thiết
require_once 'core/View.php';
require_once 'core/Route.php';
require_once 'core/Database.php';


//thực hiện load các Controller
require_once 'controllers/Controller.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/ForumController.php';

//thực hiện load các Model
require_once 'models/Model.php';
require_once 'models/User.php';
require_once 'models/Forum.php';
require_once 'models/Game.php';
require_once 'models/GameCategory.php';
require_once 'models/Order.php';
require_once 'models/Traffic.php';
require_once 'models/Product.php';
require_once 'models/ForumCategory.php';
require_once 'models/ForumComment.php';


//Bắt đầu session
session_start();

//Use các class cần thiết để khởi động ứng dụng
use Core\Route;
use Models\Model;
use Models\Traffic;
use Models\User;


//Khởi tạo base model để kết nối db
$model = new Model();

// Lấy người dùng từ session nếu có

$user = isset($_SESSION['user']) ? User::find($_SESSION['user']->id) : null;

//Kiểm tra traffic
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

$traffic = Traffic::checkAndCountUpOrInsert($ip, $user_agent, $user?->id ?? -1);

//Khởi tạo route
$route = new Route();
//register route with get method
$route->get('/', 'HomeController', 'index')->name('home');
//login
$route->get('/login', 'AuthController', 'login')->name('login');
$route->post('/login', 'AuthController', 'doLogin')->name('login.submit');
//logout
$route->get('/logout', 'AuthController', 'logout')->name('logout');
//register
$route->get('/register', 'AuthController', 'register')->name('register');
$route->post('/register', 'AuthController', 'doRegister')->name('register.submit');
//profile
$route->get('/profile', 'AuthController', 'profile')->name('user.profile');
//change password
$route->post('/change-password', 'AuthController', 'doChangePassword')->name('user.change-password.submit');


//dashboard
// $route->get('/dashboard', 'DashboardController', 'index');
$route->prefix(
    '/dashboard',
    function ($route, $prefix) {
        $route->get('/', 'DashboardController', 'index', $prefix)->name('dashboard');
        //forum
        $route->prefix(
            '/dashboard/forum',
            function ($route, $prefix) {
                $route->get('/', 'ForumController', 'index', $prefix)->name('dashboard.forum');
                //post form
                $route->get('/form-post', 'ForumController', 'postForm', $prefix)->name('dashboard.forum.form.post');
                $route->post('/form-post', 'ForumController', 'postFormSubmit', $prefix)->name('dashboard.forum.post.submit');

                // categories
                $route->get('/categories', 'ForumController', 'categories', $prefix)->name('dashboard.forum.categories');
                //category form create/update
                $route->get('/categories/form', 'ForumController', 'categoryForm', $prefix)->name('dashboard.forum.categories.form');
                $route->post('/categories/form', 'ForumController', 'categoryFormSubmit', $prefix)->name('dashboard.forum.categories.form.submit');
            }
        );
    }
);
//register route with post method


//run route
$route->run();
