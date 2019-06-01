<?php
namespace App\Middlewares;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Wechat
{
    protected $auth_key;
    protected $session;
    protected $logger;

    public function __construct(ContainerInterface $ci)
    {
        $settings = $ci->get('settings');
        $this->auth_key = $settings['auth_key'];
        $this->logger = $ci->get('logger');
        $this->session = $ci->get('session');
    }

    /**
     * Called when middleware needs to be executed.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (($openid = $request->getParam('openid', null)) !== null) {
            $this->logger->info($request->getUri());

            $sign = $request->getParam('sign', '');
            if (empty($openid) || md5($openid.'&_key='.$this->auth_key.'&t='.date('YmdH').bcdiv(date('i'), 10, 0)) != $sign) {
                $this->session->delete('openid');
                throw new \Exception('登录失败');
            }
            $this->session->set('openid', $openid);
        }
        if (!$this->session->exists('openid')) {
            throw new \Exception('http://xxxxx.com/auth.php', 302);
        }
        if (empty($this->session->get('openid'))) {
            $this->session->delete('openid');
            throw new \Exception('登录失败');
        }
        $response = $next($request, $response);
        return $response;
    }
}
