<?php

namespace Bosqu\RouteurPhp\Tests;

use PHPUnit\Framework\TestCase;
use Bosqu\RouteurPhp\Route;
use Bosqu\RouteurPhp\RouteAlreadyExistsException;
use Bosqu\RouteurPhp\RouteNotFoundException;
use Bosqu\RouteurPhp\Routeur;
use Bosqu\RouteurPhp\Tests\Fixtures\FooController;
use Bosqu\RouteurPhp\Tests\Fixtures\HomeController;


class RouteurTest extends TestCase
{
    public function test()
    {
        $router = new Routeur();

        $routeHome = new Route("home", "/", [HomeController::class, "index"]);

        $routeFoo = new Route("foo", "/foo/{bar}", [FooController::class, "bar"]);

        $routeArticle = new Route("article", "/blog/{id}/{slug}", function (string $slug, string $id) {
            return sprintf("%s : %s", $id, $slug);
        });

        $router->add($routeHome);
        $router->add($routeFoo);
        $router->add($routeArticle);

        $this->assertCount(3, $router->getRouteCollection());

        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());

        $this->assertEquals($routeHome, $router->get("home"));

        $this->assertEquals($routeHome, $router->match("/"));
        $this->assertEquals($routeArticle, $router->match("/blog/12/mon-article"));

        $this->assertEquals("Hello world !", $router->call("/"));

        $this->assertEquals(
            "12 : mon-article",
            $router->call("/blog/12/mon-article")
        );

        $this->assertEquals(
            "bar",
            $router->call("/foo/bar")
        );
    }

    public function testIfRouteNotFoundByMatch()
    {
        $router = new Routeur();
        $this->expectException(RouteNotFoundException::class);
        $router->match("/");
    }

    public function testIfRouteNotFoundByGet()
    {
        $router = new Routeur();
        $this->expectException(RouteNotFoundException::class);
        $router->get("fail");
    }

    public function testIfRouteAlreadyExists()
    {
        $router = new Routeur();
        $router->add(new Route("home", "/", function() {}));
        $this->expectException(RouteAlreadyExistsException::class);
        $router->add(new Route("home", "/", function() {}));
    }
}