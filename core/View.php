<?php

use Core\Route;

class View
{

    private static $sections = [];
    private static $extends = null;

    public static function render($path, $data = [])
    {
        try {
            global $user, $route;            
            $data[] = $user;
            extract($data);

            // // Chuyển . thành /

            // $content = self::template(self::get_contents('views/' . $path));
            // // $content = self::handlerLayout(self::get_contents('views/' . $path));
            // // $content = static::handlerSection($content);
            // // print_r(self::$extends);
            // if (self::$extends != null) {
            //     $content = self::template(self::get_contents('views/' . self::$extends));
            // }
            $content = self::template(self::get_contents('views/' . $path), $path);

            if (count(self::$sections) > 0) {
                if (self::$extends == null) {
                    return;
                }
                $layout = self::template(self::get_contents('views/' . self::$extends), self::$extends);
                foreach (self::$sections as $key => $value) {
                    // echo $value;
                    $layout = str_replace('@yield("' . $key . '")', $value, $layout);
                }
                // echo 'trong';
                return eval('?>' . $layout);
            }
            //echo 'ngoài';

            eval('?>' . $content); // eval: thực thi 1 chuỗi php
        } catch (\Throwable $th) {
            $hide_header = true;
            self::renderError($th);
        //   return  self::abort(500, $th);
        }
    }
    // renderPartial
    private static function get_contents($path)
    {
        $path = str_replace('.', '/', $path);
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


    private static function template($text, $path = null)
    {
        global $user, $route;
        $cache_name = md5($text);
        $cache_file = 'cache/views/' . $cache_name . '.php';
        if (CAHCE_VIEW) {
            if (file_exists($cache_file)) {
                //return file_get_contents($cache_file);
            }
        }

        // Route
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
        self::handlerLayout($text);
        self::handlerSection($text);
        // @extends
        $text = preg_replace('/@extends\((.+?)\)/', '', $text);
        // @section, @endsection
        $text = preg_replace('/@section\("(.+?)"\)/', '', $text);
        $text = preg_replace('/@endsection\n/', '', $text);
        // lưu vào cache       
        // thêm {{-- $path | date()--}} và đầu file
        $text = '<?php /*' . $path . ' | ' . date('Y-m-d H:i:s') . '*/ ?>' . "\n" . $text;

        if (CAHCE_VIEW) {
            if (!file_exists('cache/views')) {
                mkdir('cache/views', 0777, true);
            }
            file_put_contents($cache_file, $text);
        }

        return $text;
    }

    static  function handlerSection($input)
    {

        $regex = '/@section\("(.+?)"\)(.*?)@endsection/s';
        if (is_array($input)) {
            // echo 'ok';
            $name = $input[1];
            $content = $input[2];
            self::$sections[$name] = $content;

            return '';
        }
        return preg_replace_callback($regex, 'self::handlerSection', $input);
    }



    public static function handlerLayout($input)
    {


        // @extends
        $regex = '/@extends\((.+?)\)/';
        if (is_array($input)) {
            $path = $input[1];
            $path = str_replace('"', '', $path);
            $path = str_replace("'", '', $path);
            self::$extends = $path;
            return '';
        }
        return preg_replace_callback($regex, 'self::handlerLayout', $input);
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
        require 'views/error.blade.php';
        exit;
    }
}
