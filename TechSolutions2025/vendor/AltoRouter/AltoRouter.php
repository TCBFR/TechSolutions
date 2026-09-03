<?php
/**
 * AltoRouter - Lightweight PHP Router
 * MIT License
 */

class AltoRouter
{
    protected $routes = [];
    protected $namedRoutes = [];
    protected $basePath = '';
    protected $matchTypes = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/\.]++'
    ];

    public function __construct($routes = [], $basePath = '', $matchTypes = [])
    {
        $this->addRoutes($routes);
        $this->setBasePath($basePath);
        $this->addMatchTypes($matchTypes);
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function addMatchTypes($matchTypes)
    {
        $this->matchTypes = array_merge($this->matchTypes, $matchTypes);
    }

    public function map($method, $route, $target, $name = null)
    {
        $this->routes[] = [$method, $route, $target, $name];

        if ($name) {
            if (isset($this->namedRoutes[$name])) {
                throw new \Exception("Can not redeclare route '{$name}'");
            }
            $this->namedRoutes[$name] = $route;
        }

        return;
    }

    public function addRoutes($routes)
    {
        if (!is_array($routes) && !$routes instanceof \Traversable) {
            throw new \Exception('Routes should be an array or an instance of Traversable');
        }
        foreach ($routes as $route) {
            call_user_func_array([$this, 'map'], $route);
        }
    }

    public function generate($routeName, array $params = [])
    {
        if (!isset($this->namedRoutes[$routeName])) {
            throw new \Exception("Route '{$routeName}' does not exist.");
        }

        $route = $this->namedRoutes[$routeName];

        // Build string of parameters
        $paramsString = '';
        foreach ($params as $key => $value) {
            $paramsString .= "/{$value}";
        }

        $route = preg_replace('/\[([^\]]+)\]/', '', $route);
        $route = preg_replace_callback('/\:([a-zA-Z0-9\_\-]+)/', function($matches) use ($params) {
            if (isset($params[$matches[1]])) {
                return $params[$matches[1]];
            }
            return $matches[0];
        }, $route);

        return $this->basePath . $route;
    }

    public function match($requestUrl = null, $requestMethod = null)
    {
        $params = [];

        $requestUrl = $requestUrl ?: (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
        $requestMethod = $requestMethod ?: (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');

        // Strip query string (?a=b) from Request Url
        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }

        $lastRequestUrlChar = $requestUrl ? $requestUrl[strlen($requestUrl) - 1] : '';

        // Strip base path from request url
        $requestUrl = substr($requestUrl, strlen($this->basePath));

        // Strip trailing slash
        if ($lastRequestUrlChar === '/' && $requestUrl !== '/') {
            $requestUrl = substr($requestUrl, 0, -1);
        }

        foreach ($this->routes as $handler) {
            list($methods, $route, $target, $name) = $handler;

            $method_match = (stripos($methods, $requestMethod) !== false);

            if (!$method_match) {
                continue;
            }

            if ($route === '*') {
                $match = true;
            } elseif (isset($route[0]) && $route[0] === '@') {
                $pattern = '`' . substr($route, 1) . '`u';
                $match = preg_match($pattern, $requestUrl, $params) === 1;
            } elseif (($position = strpos($route, '[')) === false) {
                $match = strcmp($requestUrl, $route) === 0;
            } else {
                if (strncmp($requestUrl, $route, $position) !== 0) {
                    continue;
                }
                $regex = $this->compileRoute($route);
                $match = preg_match($regex, $requestUrl, $params) === 1;
            }

            if ($match) {
                if ($params) {
                    foreach ($params as $key => $value) {
                        if (is_numeric($key)) {
                            unset($params[$key]);
                        }
                    }
                }

                return [
                    'target' => $target,
                    'params' => $params,
                    'name' => $name
                ];
            }
        }

        return false;
    }

    protected function compileRoute($route)
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;

                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }

                $optional = $optional !== '' ? '?' : null;

                $pattern = '(?:'
                    . ($pre !== '' ? $pre : null)
                    . '('
                    . ($param !== '' ? "?P<$param>" : null)
                    . $type
                    . ')'
                    . $optional
                    . ')'
                    . $optional;

                $route = str_replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}