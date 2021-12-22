<?php

namespace Bosqu\RouteurPhp\Controller;

class Controller
{
    public function render(string $view, string $tittle, array $data = null)
    {
        ob_start();
        require dirname(__FILE__) . "/../../view/$view";
        $html = ob_get_clean();
        require dirname(__FILE__) . "/../../view/_partials/base.view.php";
    }
}