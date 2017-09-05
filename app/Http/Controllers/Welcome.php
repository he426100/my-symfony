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
        $logger = $this->ci->get('logger');
        try {
            $user = $db->table("users")->select(['user_id','user_name'])->first();
            return $response->withJson($user);
        } catch (QueryException $e) {
            $logger->error($e->getMessage());
        }
        return $response->getBody()->write("Hello, world");
    }
}
