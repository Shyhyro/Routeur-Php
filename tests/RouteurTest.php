<?php

namespace Bosqu\RouteurPhp\Tests;


require '../vendor/autoload.php';

use Bosqu\RouteurPhp\Controller\HomeController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;


class RouteurTest extends TestCase
{
    public function test() {
        $page = "HomeController";

        if(isset($_GET['controller'])) {

            $controller = "Bosqu\\RouteurPhp\\Controller\\" . $page;

            if(class_exists($controller)) {
                $controller = new $controller();

                if(isset($_GET['action'])) {
                    $action = filter_var($_GET['action'], FILTER_SANITIZE_STRING);

                    try {
                        $reflexion =  new ReflectionClass($controller);

                        if($reflexion->hasMethod($action)) {
                            $controller->$action();
                        }
                        else {
                            $controller->home();
                        }
                    }
                    catch (ReflectionException $e) {
                        echo $e->getMessage();
                    };
                }
                else {
                    (new $controller)->home();
                }
            }
            else {
                (new HomeController)->home();
            }
        }
        else {
            (new HomeController)->home();
        }
    }
}