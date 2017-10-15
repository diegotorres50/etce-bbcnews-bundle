<?php

namespace Etce\BbcnewsBundle\Controller;

// https://symfony.com/doc/current/components/using_components.html
// How to Install and Use the Symfony Components
// Para poder user guzzle client desde el vendor del bundle
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GuzzleHttp\Client;

// https://github.com/nrk/predis
use Predis;

class DefaultController extends Controller
{
    // http://www.bbcnewsetce.lo/bbc/diego
    public function indexAction($name)
    {

        // Para persistir y obtener datos de redis
        $client = new Predis\Client();
        $client->set('nombre', 'diego');
        $value = $client->get('nombre');

        echo "el valor en redis es: " . $value;

        // Para usarlo como un servicio inyectado
        // $this->forward('etce_bbcnews.guzzle:client', array('name' => $name));

        // Para incluir una clase de funciones
        //require $this->get('kernel')->getRootDir() . '/../src/Etce/BbcnewsBundle/vendor/guzzlehttp/guzzle/src/Client.php';

        // Ejemplo con guzzle cliente 6.0
        // https://github.com/guzzle/guzzle
        $client = new Client();
        $res = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        echo $res->getStatusCode();
        // 200
        echo $res->getHeaderLine('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // '{"id": 1420053, "name": "guzzle", ...}'

        return $this->render('EtceBbcnewsBundle:Default:index.html.twig', array('name' => $name));
    }
}
