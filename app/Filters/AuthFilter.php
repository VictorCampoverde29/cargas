<?php

namespace App\Filters;

use App\Models\BarrasPerfilModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


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
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('is_logged')) {
            return redirect()->route('login');
        }

        $uri = service('uri');
        $routePath = implode('/', $uri->getSegments());
        log_message('error','variable routepath:'.$routePath); 

        $perfil = session()->get('perfil');
        $barrasperfil=new BarrasPerfilModel();
        $urls = $barrasperfil->geturlsxperfil($perfil); 
     
       
        $matchingItems = array_filter($urls, function ($menuItem) use ($routePath) {
            return $menuItem['ruta_ci'] == $routePath;
        });

     
        if ($routePath !== 'dashboard' && $routePath!=='') {

            if (empty($matchingItems)) {
                    return redirect()->to('login/unauthorized');
            }
        }

        $acceso = 'NO'; // Valor inicial

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
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
