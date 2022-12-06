<?php
// This is psr based framework by Makhmudov Abdulaziz 2022

namespace Framework\Application;


use Framework\Http\Request;
use Framework\Http\RequestFactory;
use Framework\Http\RequestInterface;

final class Application
{

    protected $config;

    public RequestInterface $request;

    public array $components = [];

    public function __construct($config = null)
    {

        $this->request = (new RequestFactory)::fromGlobals();

        if (empty($config)) {
            throw new \Exception('config required for application');
        }

        $this->config = $config;

    }

    public function init()
    {

        $this->installComponents();
    }

    private function installComponents(): void
    {
        $config = $this->config;
        if (!empty($config['components']) && count($config['components']) > 0) {
            foreach ($config['components'] as $k => $v) {
                $this->components[$k] = new $v;
            }
        }

    }

    public function __get($name)
    {
        return isset($this->components[$name]) ? $this->components[$name] : null;
    }

    public function __set($name, $val)
    {

    }

    public function __isset($name)
    {

    }

}