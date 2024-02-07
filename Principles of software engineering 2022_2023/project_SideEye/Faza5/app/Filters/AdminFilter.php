<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if(!$session->has('korisnik'))
            return redirect ()->to (site_url ('GostController'));
         if($session->has('korisnik')){
            $korisnik = $session->get("korisnik");
            if($korisnik->PrivatnikIliKorisnik == "P")
                return redirect ()->to (site_url ('PrivatnikController'));
            else if($korisnik->PrivatnikIliKorisnik == "K")
                return redirect ()->to (site_url ('KorisnikController'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}