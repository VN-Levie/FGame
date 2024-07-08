<?php

use Core\Route;

class View
{
    public static function render($path, $data = [], $hide_header = false)
    {
        try {
            global $user, $route;
            $data[] = $hide_header;
            $data[] = $user;
            extract($data);
            //chuyển . thành /
            $path = str_replace('.', '/', $path);
            $head = self::get_contents('views/layouts/header');
            $contents = self::get_contents('views/' . $path);
            $end = self::get_contents('views/layouts/footer');
            eval('?>' . self::template($head));
            eval('?>' . self::template($contents)); // eval: thực thi 1 chuỗi php
            eval('?>' . self::template($end));
        } catch (\Throwable $th) {

            (self::renderError($th));
        }
    }

    public static function renderPartial($path, $data = [])
    {
        try {
            extract($data);
            $contents = self::get_contents('views/' . $path);
            eval('?>' . self::template($contents));
        } catch (\Throwable $th) {
            // Xóa mọi thành phần hiện có
            while (ob_get_level()) {
                ob_end_clean();
            }
            // Gọi hàm renderError
            self::renderError($th);
        }
    }

    private static function get_contents($path)
    {
        $contents = null;
        // echo $path;
        if (file_exists($path . '.php')) {
            $contents = file_get_contents($path . '.php');
        } elseif (file_exists($path . '.blade.php')) {
            $contents = file_get_contents($path . '.blade.php');
        } elseif (file_exists($path . '.html')) {
            $contents = file_get_contents($path . '.html');
        }
        if ($contents == null) {
            throw new \Exception("View <strong>'{$path}'</strong> not found.");
        }
        return $contents;
    }

    private static function template($text)
    {
        $cache_name = md5($text);
        $cache_file = 'cache/views/' . $cache_name . '.php';
        if (CAHCE_VIEW) {
            if (file_exists($cache_file)) {
                return file_get_contents($cache_file);
            }
        }
        // Route

        //route('route.name', ['id' => 1])
        $text = preg_replace('/route\((.+?)\)/', '$route->route($1)', $text);
        //{{-- , --}}
        $text = preg_replace('/\{\{--(.+?)--\}\}/s', '<?php /* $1 */ ?>', $text);

        $text = preg_replace('/\{\{(.+?)\}\}/', '<?php echo htmlspecialchars($1); ?>', $text);
        //{{ $var }}

        //{!! $var !!}
        $text = preg_replace('/\{\!\!(.+?)\!\!\}/', '<?php echo $1; ?>', $text);
        // @php
        $text = preg_replace('/@php\n/', '<?php ', $text);
        $text = preg_replace('/@endphp\n/', '?>', $text);
        $text = preg_replace('/\{%(.+?)%\}/', '<?php $1; ?>', $text);
        // @if, @else, @elseif, @endif
        $text = preg_replace('/@if(.+?)\n/', '<?php if$1: ?>', $text);
        $text = preg_replace('/@else\n/', '<?php else: ?>', $text);
        $text = preg_replace('/@elseif(.+?)\n/', '<?php elseif$1: ?>', $text);
        $text = preg_replace('/@endif\n/', '<?php endif; ?>', $text);
        // @foreach, @endforeach
        $text = preg_replace('/@foreach(.+?)\n/', '<?php foreach$1: ?>', $text);
        $text = preg_replace('/@endforeach\n/', '<?php endforeach; ?>', $text);
        // @for, @endfor
        $text = preg_replace('/@for(.+?)\n/', '<?php for$1: ?>', $text);
        $text = preg_replace('/@endfor\n/', '<?php endfor; ?>', $text);
        // @while, @endwhile
        $text = preg_replace('/@while(.+?)\n/', '<?php while$1: ?>', $text);
        $text = preg_replace('/@endwhile\n/', '<?php endwhile; ?>', $text);
        // @switch, @case, @default, @endswitch
        $text = preg_replace('/@switch(.+?)\n/', '<?php switch$1: ?>', $text);
        $text = preg_replace('/@case(.+?)\n/', '<?php case$1: ?>', $text);
        $text = preg_replace('/@default\n/', '<?php default: ?>', $text);
        $text = preg_replace('/@endswitch\n/', '<?php endswitch; ?>', $text);
        // @include
        $text = preg_replace('/@include\((.+?)\)/', '<?php include $1; ?>', $text);
        // @extends
        $text = preg_replace('/@extends\((.+?)\)/', '<?php require $1; ?>', $text);
        //lưu vào cache       
        //tìm cà xóa khoảng trắng thừa, tab, dòng trắng
        // $text = preg_replace('/\s+/', ' ', $text);
        if (CAHCE_VIEW) {
            if (!file_exists('cache/views')) {
                mkdir('cache/views', 0777, true);
            }
            file_put_contents($cache_file, $text);
        }

        // file_put_contents($cache_file, $text);
        return $text;
    }

    public static function renderError($th)
    {
        // Warning: require(views/home/index.php): Failed to open stream: No such file or directory in D:\work\Hieu\FGame\core\View.php on line 10

        $text = '';
        // clear fix
        $text .= '<div class="clearfix"></div>';
        $text .= '<div class="container"><div class="alert alert-danger"><strong>Xảy ra lỗi</strong><br><br>';
        $text .=  '<pre>';
        $text .=  '<p>' . $th->getMessage() . "</p>";
        $text .=  '<p>' . $th->getFile() . " on line " . $th->getLine() . "</p>";
        $text .=  '<p>' . $th->getTraceAsString() . '</p>';

        $text .=  '</pre>';
        $text .=  '</div></div>';
        // self::abort(500, $text);
        die($text);
    }

    public static function abort($code, $message = null)
    {
        $data = [
            'error_code' => $code,
            'error_message' => $message
        ];
        http_response_code($code);
        extract($data);
        require 'views/error.php';
        exit;
    }
}
