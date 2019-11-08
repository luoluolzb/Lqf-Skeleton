<?php
declare(strict_types=1);

namespace App\Controller;

use Lqf\AppFactory;
use Medoo\Medoo;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

/**
 * User RESTful API Controller
 */
class UserController
{
    /**
     * @var Medoo
     */
    private $medoo;

    public function __construct()
    {
        $container = AppFactory::getInstance()->getContainer();
        $this->medoo = $container->get('medoo');
    }

    public function add(Request $request): Response
    {
        $params = $request->getParsedBody();
        $res = $this->medoo->insert('user', $params);
        return new JsonResponse([
            'status' => $res !== false,
            'user_id' => $this->medoo->id(),
        ]);
    }

    public function delete(Request $request, array $params): Response
    {
        $res = $this->medoo->delete('user', $params);
        return new JsonResponse([
            'status' => ($res !== false),
        ]);
    }

    public function update(Request $request, array $where): Response
    {
        $user = $request->getParsedBody();
        $res = $this->medoo->update('user', $user, $where);
        return new JsonResponse([
            'status' => $res !== false,
            'user' => $this->medoo->get('user', '*', $where),
        ]);
    }
 
    public function get(Request $request, array $params): Response
    {
        return new JsonResponse($this->medoo->get('user', '*', $params));
    }

    public function all(Request $request): Response
    {
        return new JsonResponse($this->medoo->select('user', '*'));
    }
}
