<?php

namespace Etce\BbcnewsBundle\Controller;

// https://symfony.com/doc/current/components/using_components.html
// How to Install and Use the Symfony Components
// Para poder user guzzle client desde el vendor del bundle
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use GuzzleHttp\Client;

// https://github.com/nrk/predis
use Predis;

class DefaultController extends Controller
{
    // http://www.bbcnewsetce.lo/bbc/diego
    public function indexAction($name)
    {
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

    // http://www.bbcnewsetce.lo/redis
    public function redisAction()
    {
        // Para persistir y obtener datos de redis
        $arguments = array(
            'scheme'        => 'tcp',
            'host'          => '127.0.0.1',
            'port'          => 6379,
            'database'      => 8,
            'password'      => null,
            'timeout'       => 5.0,
            'alias'         => null,
            'throw_errors'  => true
        );

        try {

            // Funciona para lanzar una excepcion (ver mi listener creado en Listeners y en Services.yml)
            //throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, "Some description");

            // Levanta redis
            $client = new Predis\Client($arguments);

            // Verifica si existe una key
            $exists = $client->exists('12345601');

            // Setea una key y su valor
            $client->set('12345601', 'http://bbcnews.com/noticias/12345601');

            // Obtiene el valor de una key
            $value = $client->get('12345601');

            //Get list of all keys. This creates an array of keys from the redis-cli output of "KEYS *"
            $list = $client->keys("*");
            //Optional: Sort Keys alphabetically
            sort($list);

            $allKeys = array();

            //Loop through list of keys
            foreach ($list as $key) {
                //Get Value of Key from Redis
                $val = $client->get($key);

                //Print Key/value Pairs
                $allKeys[] = "<b>Key:</b> $key <br /><b>Value:</b> $val <br /><br />";
            }
            //Disconnect from Redis
            //$client->disconnect();

            // Borra todas las keys de la base de datos
            $flush = $client->flushdb();
        } catch (\Exception $ex) {
            // Si el servicio del listener esta prendido no funcionara
            $class = get_class($ex);
            if ($class == 'Symfony\Component\HttpKernel\Exception\HttpException') {
                // create a response for HttpException
                $return = array('errorbuu'=>array('code'=>500,'message'=>$ex->getMessage()));
                return new Response(json_encode($return), 200, array('Content-Type' => 'application/json'));
            } else {
                // create a response for all other Exceptions
                print_r('otra cosa'); die;
            }
        }

        $res = array(
            'exists' => intval($exists),
            'value' => $value,
            'fush' => $flush,
            'all_keys' => $allKeys
        );

        $response = new Response(json_encode(array($res)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}
