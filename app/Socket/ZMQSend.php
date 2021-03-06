<?php
/**
 * Created by PhpStorm.
 * User: jashka
 * Date: 02.11.16
 * Time: 12:58
 */

namespace Socket;

/**
 * Class ZMQSend
 * @package Socket
 */
class ZMQSend
{
    /**
     * @var \ZMQSocket|null
     */
    protected static $connection = null;

    /**
     * @param array|string|int $data
     */
    public static function send($data) {
        try {
            $data['category'] = config('zmq_socket.push_channel');
            self::getConnection()->send(json_encode($data));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return null|\ZMQSocket
     */
    public static function getConnection () {
        if (self::$connection === null) {
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            self::$connection = $socket->connect(config('zmq_socket.url') . ':' . config('zmq_socket.port'));
        }
        
        return self::$connection;
    }
}