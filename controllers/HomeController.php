<?php

use Controllers\Controller;
use Models\Forum;
use Models\Game;
use Models\GameCategory;

class HomeController extends Controller {
    public function index() {
        $forums = Forum::all();
        $game_categories = GameCategory::all();
        $game = Game::all();
        $this->view('home/index');
    }

    public function about() {      
        $this->view('home/about');
    }
}
