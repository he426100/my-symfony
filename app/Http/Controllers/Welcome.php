<?php
namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Welcome extends Controller
{
    public function index(Request $request, Response $response)
    {
        /** @var DatabaseManager $db */
        $db = $this->ci->get('db');
        try {
            $user = $db->table("users")->select(['user_id','user_name'])->first();
            return $response->getBody()->write(json_encode($user));
        } catch (QueryException $e) {
            echo $e->getMessage();
        }
        $logger = $this->ci->get('logger');
        $logger->info('a logger info');
        $response->getBody()->write("Hello, world");
        return $response;
    }
}
