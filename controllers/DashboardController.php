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
        $forums = Forum::all();
        $comments = count(ForumComment::all());
        $game_categories = GameCategory::all();
        $games = Game::all();
        $users = User::all();
        $last_user = User::last();
        $sum_total = Order::sum('total');
        $traffics = Traffic::sum('count_up');
        $traffics_unique = count(Traffic::all());
        $oders = Order::whereWiths(['Order', 'orders'], [['Product', 'products'], ['User', 'users']]);
        // print_r($oders);
        $data = [
            'title' => 'Dashboard',
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
        $this->view('dashboard/index', $data);
    }

    public function about()
    {
        $this->view('home/about');
    }
}
