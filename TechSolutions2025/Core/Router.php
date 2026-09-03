<?php

namespace Core;

use AltoRouter;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class Router extends AltoRouter
{
    private array $params=[];

    public function start():string
    {
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $match = $this->match(null, $method);

        if(!$match){
            abort();
        }

        $this->params=$match['params'];

        return base_path("controller/".$match['target']);
    }

    public function get(string $url, string $ctrl, ?string $name = null): void
    {
        $this->addRoute($url, $ctrl, 'GET', $name);
    }

    public function post(string $url, string $ctrl, ?string $name = null): void
    {
        $this->addRoute($url, $ctrl, 'POST', $name);
    }

    public function put(string $url, string $ctrl, ?string $name = null): void
    {
        $this->addRoute($url, $ctrl, 'PUT', $name);
    }

    public function patch(string $url, string $ctrl, ?string $name = null): void
    {
        $this->addRoute($url, $ctrl, 'PATCH', $name);
    }

    public function delete(string $url, string $ctrl, ?string $name = null): void
    {
        $this->addRoute($url, $ctrl, 'DELETE', $name);
    }

    private function addRoute(string $url, string $ctrl, string $method, ?string $name = null): void
    {
        $this->map($method, $url, $ctrl, $name);
    }

    public function params(): array
    {
        return $this->params;
    }
}
