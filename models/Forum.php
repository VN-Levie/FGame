<?php

namespace Models;

use PDO;

class Forum extends Model
{
    protected static $table = 'forums';
    public $id;
    public $category_id;
    public $title;
    public $content;
    public $views;
    public $soft_delete = 0;
    public $hide = 0;
    public $archive_by_category = 0;
    public $user_id;
    public $updated_at;
    public $created_at;

    public function getCategory()
    {
        return ForumCategory::find($this->category_id);
    }

    // Lấy comments
    public function getComments()
    {
        return ForumComment::whereWiths([['User']], ['forum_id' => $this->id]);
    }

    public static function countUpView($id)
    {
        $forum = self::find($id);
        $forum->views += 1;
        return $forum->save();
    }

    //lấy ảnh thmbnail từ content <p><img alt="" src="/assets/images/logo.png" style="float:left; height:150px; width:150px" />Test b&agrave;i viết c&oacute; ảnh</p>
    public function getThumbnail()
    {
        $content = $this->content;
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            return $tag->getAttribute('src');
        }
        return '/assets/images/logo.png';
    }

    //get short content (remove html tag)
    public function getShortContent($l = 3)
    {
        return strip_tags(limit_word($this->content, $l));
    }

    //get user
    public function getUser()
    {
        return User::find($this->user_id);
    }
}
