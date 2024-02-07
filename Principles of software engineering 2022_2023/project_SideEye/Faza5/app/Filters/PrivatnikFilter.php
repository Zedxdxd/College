<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PrivatnikFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if(!$session->has('korisnik'))
            return redirect ()->to (site_url ('GostController'));
        if($session->has('korisnik')){
            $korisnik = $session->get("korisnik");
            if($korisnik->PrivatnikIliKorisnik == "K")
                return redirect ()->to (site_url ('KorisnikController'));
            else if($korisnik->PrivatnikIliKorisnik == "A")
                return redirect ()->to (site_url ('AdminController'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // do something
    }
}