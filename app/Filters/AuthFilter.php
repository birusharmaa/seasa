<?php

namespace App\Filters;
 
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use App\Filters\Exception;
use App\Models\ValidateUser;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {   
        $request = service('request');
        $key = getenv('JWT_SECRET_KEY');
    
        
        $email     = 'validate@gmail.com';//$request->getHeader("email")->getValue();
        $password    = '$2y$10$kbGMACNVSVBdK34wXJYOa.GBVKv6V3dj7zy.3R9FGq/kOS2ULEUw6';//$request->getHeader("password")->getValue();

        if(is_null($email) || empty($email)) {
            $response = service('response');
            $response->setBody('Access denied.');
            $response->setStatusCode(401);
            return $response;
        }
        
        if(!empty($email) && !empty($password)){
            $validate_model = new ValidateUser();
            $condition = ['email' => $email];
            $data = $validate_model->where($condition)->first();
            
            if (!password_verify($data['password'], $password)) {
                echo "Invalid credentials";
                exit();
            }
        }
        
        
 
        // try {
        //     $decoded = JWT::decode($token, new Key($key, 'HS256'));
        // } catch (Exception $ex) {
            
        //     $response = service('response');
        //     $response->setBody('Access denied 2');
        //     $response->setStatusCode(401);
        //     return $response;
        // }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
