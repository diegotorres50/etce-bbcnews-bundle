<?php

namespace Etce\BbcnewsBundle\Controller;

// https://symfony.com/doc/current/components/using_components.html
// How to Install and Use the Symfony Components
// Para poder user guzzle client desde el vendor del bundle
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GuzzleHttp\Client;

class DefaultController extends Controller
{
    // http://www.bbcnewsetce.lo/bbc/diego
    public function indexAction($name)
    {
        // Para usarlo como un servicio inyectado
        // $this->forward('etce_bbcnews.guzzle:client', array('name' => $name));

        // Para incluir una clase de funciones
        //require $this->get('kernel')->getRootDir() . '/../src/Etce/BbcnewsBundle/vendor/guzzlehttp/guzzle/src/Client.php';

        // Ejemplo con guzzle cliente 5.3
        // http://docs.guzzlephp.org/en/5.3/
        $client = new Client();
        $res = $client->get('http://guzzlephp.org');
        //$res = $client->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // {"type":"User"...'
        var_export($res->json());
        // Outputs the JSON decoded data

        return $this->render('EtceBbcnewsBundle:Default:index.html.twig', array('name' => $name));
    }
}
