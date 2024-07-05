# Cấu trúc dự án Fgame: Diễn đàn trao đổi game

#### 1. Cấu trúc dự án
Dự án sẽ được xây dựng theo mô hình MVC (Model-View-Controller) với PHP Core, MySQL sử dụng PDO, HTML, CSS, JavaScript và AJAX. Cấu trúc thư mục dự kiến sẽ như sau:

```
fgame/
├── index.php
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   └── scripts.js
│   └── images/
├── config/
│   └── config.php
├── controllers/
│   ├── HomeController.php
│   ├── GameController.php
│   ├── AuthController.php
│   ├── ForumController.php
│   └── ...
├── models/
│   ├── Game.php
│   ├── User.php
│   ├── Forum.php
│   ├── Post.php
│   └── ...
├── views/
│   ├── templates/
│   │   ├── header.php
│   │   └── footer.php
│   ├── home/
│   │   └── index.php
│   ├── game/
│   │   ├── list.php
│   │   ├── detail.php
│   │   └── ...
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── ...
│   ├── forum/
│   │   ├── index.php
│   │   ├── thread.php
│   │   ├── new_thread.php
│   │   └── ...
│   └── ...
└── core/
    ├── Database.php
    ├── Controller.php
    ├── Model.php
    └── View.php
```

#### 2. Cấu trúc thiết kế cơ sở dữ liệu
Cơ sở dữ liệu sẽ bao gồm các bảng chính như sau:

- `users`: Quản lý thông tin người dùng.
- `games`: Quản lý thông tin game.
- `threads`: Quản lý các chủ đề thảo luận.
- `posts`: Quản lý các bài viết trong các chủ đề thảo luận.
- `comments`: Quản lý bình luận về các game và các bài viết trong diễn đàn.

```sql

CREATE TABLE `forum` (
 `id` int NOT NULL AUTO_INCREMENT,
 `parent_id` int DEFAULT NULL,
 `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'm_thread',
 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `games` (
 `id` int NOT NULL AUTO_INCREMENT,
 `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
 `description` text COLLATE utf8mb4_general_ci,
 `release_date` date DEFAULT NULL,
 `platform_id` int DEFAULT NULL,
 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `game_categories` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
 `description` text COLLATE utf8mb4_general_ci,
 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `platforms` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` int NOT NULL,
 `description` text COLLATE utf8mb4_general_ci,
 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `users` (
 `id` int NOT NULL AUTO_INCREMENT,
 `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
 `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
 `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
```

#### 3. Chi tiết cấu trúc MVC

**Model:**
- `Game.php`, `User.php`, `Forum.php`, `Post.php`: Các model này sẽ tương tác với cơ sở dữ liệu để thực hiện các thao tác CRUD (Create, Read, Update, Delete).

**View:**
- Các view sẽ chứa các file HTML/PHP để hiển thị giao diện người dùng. Các template header và footer sẽ được sử dụng lại trong các trang khác nhau.
- `views/forum/index.php`: Trang hiển thị danh sách các chủ đề thảo luận.
- `views/forum/thread.php`: Trang hiển thị chi tiết một chủ đề cùng các bài viết.
- `views/forum/new_thread.php`: Trang tạo chủ đề mới.

**Controller:**
- `HomeController.php`, `GameController.php`, `AuthController.php`, `ForumController.php`: Các controller này sẽ điều hướng các yêu cầu từ người dùng, tương tác với model và trả về view tương ứng.

**Core:**
- `Database.php`: Quản lý kết nối cơ sở dữ liệu.
- `Controller.php`: Lớp cơ sở cho tất cả các controller, chứa các phương thức tiện ích chung.
- `Model.php`: Lớp cơ sở cho tất cả các model, chứa các phương thức tiện ích chung.
- `View.php`: Lớp quản lý view, hỗ trợ render các view với dữ liệu truyền vào.

**Config:**
- `config.php`: Chứa các cấu hình của dự án như thông tin kết nối cơ sở dữ liệu.

**Index.php:**
- File chính của dự án, điều hướng route và gọi controller tương ứng dựa trên yêu cầu của người dùng.

#### Hướng phát triển dự án

1. **Thiết lập cấu trúc dự án:**
   - Tạo các thư mục và file cần thiết.
   - Thiết lập kết nối cơ sở dữ liệu trong `config/config.php`.

2. **Xây dựng lớp core:**
   - `Database.php`: Quản lý kết nối cơ sở dữ liệu bằng PDO.
   - `Controller.php`, `Model.php`, `View.php`: Xây dựng các lớp cơ sở cho MVC.

3. **Xây dựng các model:**
   - `Game.php`, `User.php`, `Forum.php`, `Post.php`: Tạo các model tương ứng với bảng trong cơ sở dữ liệu.

4. **Xây dựng các controller:**
   - `HomeController.php`, `GameController.php`, `ForumController.php`: Xây dựng các phương thức xử lý yêu cầu.

5. **Xây dựng các view:**
   - Tạo các file giao diện trong thư mục `views/` và các template chung như header, footer.
   - Thiết kế giao diện cho diễn đàn, bao gồm trang chủ đề, trang chi tiết chủ đề và trang tạo chủ đề mới.

6. **Thiết lập routing trong `index.php`:**
   - Điều hướng các yêu cầu đến controller và phương thức tương ứng.

#### Cấu trúc ví dụ cho file index.php

```php
<?php
require_once 'core/Database.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';

$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$methodName = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

if (file_exists("controllers/$controllerName.php")) {
    require_once "controllers/$controllerName.php";
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        echo "Method $methodName not found.";
    }
} else {
    echo "Controller $controllerName not found.";
}
?>
```

