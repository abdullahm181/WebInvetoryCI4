<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FilterAdmin implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->userlevelid == null) {
            return redirect()->to('/auth/index');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (session()->userlevelid == 1) {
            return redirect()->to('/main/index');
        }
    }
}
