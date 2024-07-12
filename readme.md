

# Fgame: Diễn đàn trao đổi game

Fgame là một diễn đàn trao đổi game, nơi người dùng có thể thảo luận về các game, đăng bài viết, bình luận và nhiều hơn nữa. Dự án này được xây dựng bằng PHP Core, MySQL (sử dụng PDO), HTML, CSS, JavaScript và AJAX theo mô hình MVC (Model-View-Controller).

## 1. Cấu trúc dự án

Dự án sẽ có cấu trúc thư mục như sau:

```markdown
fgame/
├── assets/
├── cache/
│   └── views/
├── commands/
│   ├── GreetCommand.php
│   ├── MakeControllerCommand.php
│   ├── MakeModelCommand.php
│   ├── MakeViewCommand.php
│   └── ServeCommand.php
├── controllers/
│   ├── AuthController.php
│   ├── Controller.php
│   ├── DashboardController.php
│   ├── ForumController.php
│   ├── GameController.php
│   ├── HomeController.php
│   └── ProductController.php
├── core/
│   ├── Application.php
│   ├── ConsoleColor.php
│   ├── Database.php
│   ├── Helper.php
│   ├── Route.php
│   └── View.php
├── models/
├── views/
│   ├── auth/
│   ├── dashboard/
│   ├── home/
│   ├── layouts/
│   └── error.blade.php
├── .gitignore
├── .htaccess
├── artisan
├── hieu_fgame.sql
├── index.php
├── readme.md
└── run.bat
```

## 2. Cấu trúc thiết kế cơ sở dữ liệu

Cơ sở dữ liệu sẽ bao gồm các bảng chính như sau:

- `users`: Quản lý thông tin người dùng.
- `games`: Quản lý thông tin game.
- `game_categories`: Quản lý các danh mục game.
- `platforms`: Quản lý các nền tảng chơi game.
- `forums`: Quản lý các chủ đề thảo luận.
- `forum_categories`: Quản lý các danh mục chủ đề thảo luận.
- `forum_comments`: Quản lý các bình luận trong chủ đề thảo luận.
- `forum_edited_histories`: Quản lý lịch sử chỉnh sửa chủ đề thảo luận.
- `carts`: Quản lý giỏ hàng của người dùng.
- `orders`: Quản lý đơn hàng.
- `payment_methods`: Quản lý phương thức thanh toán.
- `products`: Quản lý các sản phẩm.
- `product_categories`: Quản lý các danh mục sản phẩm.
- `digital_template`: Quản lý các mẫu kỹ thuật số.
- `customer_address`: Quản lý địa chỉ khách hàng.
- `reactions`: Quản lý các phản ứng (reaction) cho các chủ đề thảo luận.
- `traffics`: Quản lý lưu lượng truy cập.
Dưới đây là cấu trúc chi tiết của các bảng:

```sql
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` int NOT NULL DEFAULT '0' COMMENT '0: user,1: seller, 3: mod, 5: s-mod, 8: admin, 9: s-admin',
  `baned` int NOT NULL DEFAULT '0',
  `soft_delete` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `games` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `release_date` date DEFAULT NULL,
  `platform_id` int DEFAULT NULL,
  `game_category_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `game_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `platforms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `forums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `soft_delete` tinyint(1) NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `archive_by_category` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `forum_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `soft_delete` tinyint(1) NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `forum_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `forum_id` int NOT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `forum_edited_histories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `forum_id` int NOT NULL,
  `previous_content` text COLLATE utf8mb4_general_ci NOT NULL,
  `new_content` text COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `orders` (
  `id`

 int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `product_price` int NOT NULL,
  `total` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `customer_note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seller_note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_address_id` int NOT NULL,
  `payment_method_id` int NOT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payment_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'cod,card,paypal',
  `detail` varchar(561) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `is_digital` tinyint(1) NOT NULL DEFAULT '0',
  `digital_info` json DEFAULT NULL,
  `digital_template_id` int NOT NULL DEFAULT '0',
  `category_id` int NOT NULL,
  `status` int NOT NULL COMMENT '0 out-stock,1 in-stock, 2 Waiting for import, 3 new',
  `pinned` tinyint(1) NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `digital_template` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'digital template',
  `description` text COLLATE utf8mb4_general_ci,
  `template` json DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `customer_address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `thread_id` int NOT NULL,
  `user_id` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `traffics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_id` int DEFAULT '-1',
  `count_up` int NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## 3. Các chức năng chính

- Quản lý người dùng (đăng ký, đăng nhập, chỉnh sửa thông tin, v.v.)
- Quản lý game và danh mục game
- Quản lý nền tảng chơi game
- Quản lý chủ đề thảo luận và bình luận trong diễn đàn
- Quản lý lịch sử chỉnh sửa chủ đề
- Quản lý giỏ hàng và đơn hàng
- Quản lý phương thức thanh toán
- Quản lý sản phẩm và danh mục sản phẩm
- Quản lý mẫu kỹ thuật số
- Quản lý địa chỉ khách hàng
- Quản lý phản ứng (reaction) cho các chủ đề thảo luận
- Quản lý lưu lượng truy cập

## 4. Cài đặt

### Các tính năng chính

- **Kiến trúc MVC**: Tổ chức ứng dụng thành các model, view và controller, giúp dễ dàng quản lý và mở rộng.
- **Routing tùy chỉnh**: Sử dụng cơ chế định tuyến tùy chỉnh được định nghĩa trong [`index.php`] để ánh xạ các URL tới các controller và phương thức tương ứng.
- **Giao diện dòng lệnh (CLI)**: Cung cấp công cụ CLI [`artisan`] để thực hiện các nhiệm vụ như tạo model, controller, và view.
- **Quản lý cơ sở dữ liệu**: Quản lý kết nối và thao tác cơ sở dữ liệu thông qua lớp [`Database.php`] sử dụng PDO.
- **Quản lý phiên làm việc**: Xử lý các phiên làm việc của người dùng, bao gồm trạng thái đăng nhập và duy trì dữ liệu người dùng qua các yêu cầu.
- **Giám sát lưu lượng**: Bao gồm chức năng giám sát và ghi lại lưu lượng ứng dụng trong [`models/Traffic.php`].

### Bắt đầu

1. **Cài đặt môi trường**: Đảm bảo PHP và MySQL đã được cài đặt trên hệ thống của bạn.
2. **Clone Repository**: Clone dự án về máy tính của bạn.
   ```sh
   git clone https://github.com/username/fgame.git
   ```
3. **Cấu hình cơ sở dữ liệu**: Tạo cơ sở dữ liệu MySQL và nhập [`hieu_fgame.sql`] để thiết lập cấu trúc cơ sở dữ liệu ban đầu.
4. **Cấu hình ứng dụng**: Cập nhật các cài đặt kết nối cơ sở dữ liệu trong [`core/Database.php`].
5. **Chạy ứng dụng**: Sử dụng máy chủ web để phục vụ tệp [`index.php`] ở thư mục gốc của dự án. Hoặc sử dụng lệnh sau để chạy ứng dụng với Artisan CLI:
   ```sh
   php artisan serve --host=127.0.0.1 --port=8000
   ```

## Routing

Ứng dụng sử dụng cơ chế định tuyến đơn giản để điều hướng các yêu cầu tới controller và phương thức phù hợp dựa trên URL. Điều này được thực hiện thông qua [`.htaccess`] cho máy chủ Apache, chuyển hướng tất cả lưu lượng tới [`index.php`] nơi logic định tuyến được định nghĩa.

## Công cụ CLI

Script [`artisan`] cung cấp giao diện dòng lệnh để thực hiện các nhiệm vụ phổ biến như tạo model, controller, và view. Nó đơn giản hóa quá trình phát triển bằng cách tự động hóa việc tạo mã mẫu.

### Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7
- Apache hoặc Nginx

### Hướng dẫn cài đặt

1. Clone repository:
   ```sh
   git clone https://github.com/vn-levie/fgame.git
   ```

2. Cấu hình cơ sở dữ liệu trong file `config/config.php`.

3. Chạy các lệnh SQL để tạo bảng và dữ liệu ban đầu trong cơ sở dữ liệu của bạn.

4. Truy cập trang web thông qua trình duyệt web của bạn.

## 5. Góp ý và phát triển

Chúng tôi luôn chào đón các góp ý và đề xuất từ cộng đồng. Nếu bạn muốn đóng góp, vui lòng tạo một pull request hoặc mở một issue mới trên GitHub.
