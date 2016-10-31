<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 31.10.16
 * Time: 9:46
 */

namespace Helpers\JSocket;

use Config;
use Crypt;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\App;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
/**
 * Class Pusher
 * @Source: http://socketo.me/docs/push (Modified for Laravel)
 * @package Barrot\Laratchet\Server
 */
class Pusher implements WampServerInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = [];
    public function __construct()
    {
        $this->clients = [];
    }
    /**
     * Handle the push event
     *
     * @param $config
     */
    public function onPush($config)
    {
        echo __FUNCTION__;
        $config = json_decode($config, true);
        switch ($config['mode'])
        {
            case 'message' :
                $this->message($config);
                break;
            case 'broadcast' :
                $this->broadcast($config);
        }
    }
    /**
     * Send a message to a set of users
     *
     * @param $config
     */
    private function message($config)
    {
        print 2;
        if (!array_key_exists('recipients', $config))
        {
            return;
        }
        foreach ($this->clients AS $id => $client)
        {
            if (in_array($client->session->getId(), $config['recipients']))
            {
                $client->send(json_encode($config['data']));
            }
        }
    }
    /**
     * Broadcast a message on a channel
     *
     * @param $config
     */
    private function broadcast($config)
    {
        if (!array_key_exists($config['channel'], $this->subscribedTopics))
        {
            return;
        }
        $topic = $this->subscribedTopics[$config['channel']];
        return $topic->broadcast($config['data']);
    }
    /**
     * Assign laravel sessions to connections
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        /*$session = (new SessionManager(App::getInstance()))->driver();
        //Push server and laravel have to work on the same domain to share cookies
        $cookies = $conn->WebSocket->request->getCookies();
        //Get the laravel cookie
        $laravelCookie = urldecode($cookies[Config::get('session.cookie')]);
        $idSession = Crypt::decrypt($laravelCookie);
        $session->setId($idSession);
        //Assign the laravel session to the current connection
        $conn->session = $session;*/
        //Register the client
        echo 'connect';
        $this->clients[$conn->resourceId] = $conn;
    }
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        print 4;
        //$conn->send(json_encode([$topic]));
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        print 5;
        //$conn->send(json_encode([$topic]));
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e->getMessage();
        $conn->close();
    }
    public function onClose(ConnectionInterface $conn)
    {
        echo 'close';
    }
}