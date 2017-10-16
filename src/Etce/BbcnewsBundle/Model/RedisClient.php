<?php

namespace Etce\BbcnewsBundle\Model;

// https://github.com/nrk/predis
use Predis;

/**
* RedisClient Class is The main class you will use to connect to Redis and execute
* commands.
*
* RedisClient is a class that contains methods for using redis-cli
* with commands
*
* @package      XalokCMS
* @subpackage   BBCNewsBundle
* @filesource
* @category     ETCE
* @author       Diego Torres <diecam@eltiempo.com>
* @version      1.0
* @access       public
* @see          http://www.wiki.com/etce
* @copyright    Copyright 2017, EL TIEMPO Casa Editorial
* @example      $redis = RedisClient::getInstance(array(
* @example                                          'scheme'        => 'tcp',
* @example                                          'host'          => '127.0.0.1',
* @example                                          'port'          => 6379,
* @example                                          'database'      => 8,
* @example                                          'password'      => null,
* @example                                          'timeout'       => 5.0,
* @example                                          'alias'         => null,
* @example                                          'throw_errors'  => true),
* @example                                         array());
* @example      $redisClient = $redis->getClient();
* @example      $redisClient = $redisClient->get('some_key');
* @internal     the class uses the private methods only for ETCE projects
* @license      http://opensource.org/licenses/gpl-license.php GNU Public License
* @link         https://github.com/nrk/predis Predis
* @property mixed $regular regular read/write property
* @property-read int $foo the foo prop
* @property-write string $bar the bar prop
* @todo finish the functions on this page
* @tutorial https://github.com/nrk/predis/wiki
*/
class RedisClient
{
    private $_client = null;

    private static $_instance; // The singleton instance

    /*
    Get an instance of the Redis
    @return Instance
    */
    public static function getInstance($arguments = [], $options = [])
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self($arguments, $options);
        }

        return self::$_instance;
    }

    /**
    * returns the conexion to redis after connects to the Redis server
    *
    * @param  array  $arguments Arguments specify connection settings, set up
    *                           aggregate connections and so onConnection
    *                           parameters can be supplied either in the
    *                           form of URI strings or named arrays.
    * @param  array  $options   Options configure redis behaviour and are managed
    *                           using a mini DI-alike container and their values
    *                           can be lazily initialized only when needed.
    * @return object    The main object you will use to connect to Redis and execute
    *                   commands.
    * @access public
    * {@source }
    * displays without a break in the flow
    * @todo client conexion could use a string uri as param as well
    */
    public function __construct($arguments = [], $options = [])
    {
        try {
            $this->_client = new Predis\Client($arguments, $options);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    // Magic method clone is empty to prevent duplication of client
    private function __clone()
    {
    }

    // Get redis client
    public function getClient()
    {
        return $this->_client;
    }

    // Set multiple hash fields
    public function setHashMultipleFields($key, $fields = [])
    {
        $this->_client->hmset($key, $fields);
    }
}
