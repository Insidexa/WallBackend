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
    public static function send($data) {
        try {
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $data['category'] = 'wall';
            
            $socket->send(json_encode($data));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}