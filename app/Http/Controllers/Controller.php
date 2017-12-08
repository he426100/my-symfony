<?php
namespace App\Http\Controllers;

use Interop\Container\ContainerInterface;

abstract class Controller
{
    protected $ci;
    protected $db;
    protected $view;
    protected $logger;
    protected $session;

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->db = $this->ci->get('db');
        $this->view = $this->ci->get('view');
        $this->logger = $this->ci->get('logger');
        $this->session = $ci->get('session');
    }
}
