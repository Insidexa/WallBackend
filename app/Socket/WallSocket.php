<?php

namespace Socket;

use App;
use Auth;
use Config;
use Crypt;
use App\User;
use Faker\Provider\Image;
use Illuminate\Session\SessionManager;


use App\Wall;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Repositories\CommentRepository;
use Repositories\ImageRepository;
use Repositories\LikeRepository;
use Repositories\WallRepository;

class WallSocket implements MessageComponentInterface
{

    /**
     * @var array
     */
    protected $users;

    public function __construct()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if (!(error_reporting() & $errno)) {
                return;
            }

            switch ($errno) {
                case E_USER_ERROR:
                    echo "My ERROR [$errno] $errstr" . PHP_EOL;
                    echo "  Fatal error in line $errline file $errfile";
                    echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")" . PHP_EOL;
                    echo "Shutdown ws server..." . PHP_EOL;
                    exit(1);
                    break;

                case E_USER_WARNING:
                    echo "My WARNING [$errno] $errstr - line $errline" . PHP_EOL;
                    break;

                case E_USER_NOTICE:
                    echo "My NOTICE [$errno] $errstr - line $errline" . PHP_EOL;
                    break;

                default:
                    echo "Undefined error: [$errno] file $errfile, error: $errstr - $errline" . PHP_EOL;
                    break;
            }

            return true;
        });

        $this->users = [];
    }

    public function onOpen(ConnectionInterface $connection)
    {
        $this->users[$connection->resourceId]['connection'] = $connection;
        printf("New connection! (%s)\n", $connection->resourceId);

        /*$session = (new SessionManager(App::getInstance()))->driver();
        // Get the cookies
        $cookies = $connection->WebSocket->request->getCookies();
        // Get the laravel's one
        $laravelCookie = urldecode($cookies[Config::get('session.cookie')]);
        // get the user session id from it
        $idSession = Crypt::decrypt($laravelCookie);
        // Set the session id to the session handler
        $session->setId($idSession);
        // Bind the session handler to the client connection
        $connection->session = $session;*/
    }

    protected function like($data) {
        return LikeRepository::like($data);
    }

    protected function removeWall($id) {
        WallRepository::delete($id);
        ImageRepository::deleteWhereWallId($id);
        CommentRepository::deleteWhereWallId($id);
        LikeRepository::deleteWhereWallId($id);
    }

    protected function addWall ($wallData) {
        $wall = WallRepository::create($wallData);
        ImageRepository::create($wallData, $wall->id);
        
        return WallRepository::get($wall->id);
    }

    protected function addComment($commentData) {
        
        return CommentRepository::create($commentData);
    }

    protected function noInteresting($id) {
        return App\Ignore::create([
            'user_id' => 1,
            'wall_id' => $id
        ]);
    }

    protected function removeComment($data) {
        CommentRepository::delete($data);
        LikeRepository::deleteWhereData($data);
    }
    
    protected function updateComment($comment) {
        return CommentRepository::update($comment);
    }

    protected function updateWall($wallData) {
        ImageRepository::checkImages($wallData);
        return WallRepository::update($wallData);
    }
    
    public function onMessage(ConnectionInterface $from, $message)
    {
        /*$from->session->start();

        $idUser = $from->session->get(Auth::getName());

        if (!isset($idUser)) {
            throw new Exception('access denied');
        }*/
        
        $userMessage = json_decode($message);

        switch ($userMessage->action) {

            case 'get_walls':
                $from->send(json_encode([
                    'action' => 'client_walls',
                    'response' => Wall::with('user')->with('images')->with('comments')->get()
                ]));
                break;

            case 'user_add_wall':
                $wall = $this->addWall($userMessage->wall);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $wall,
                        'action' => 'client_add_wall'
                    ]));
                }
                break;

            case 'user_add_comment':
                $comment = $this->addComment($userMessage);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $comment,
                        'action' => 'client_add_comment'
                    ]));
                }
                break;

            case 'user_update_wall':
                $wall = $this->updateWall($userMessage->wall);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $wall,
                        'action' => 'client_update_wall'
                    ]));
                }
                break;

            case 'user_remove_wall':
                $this->removeWall($userMessage->id);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $userMessage->index,
                        'action' => 'client_remove_wall'
                    ]));
                }
                break;

            case 'user_wall_like':
                $response = $this->like($userMessage);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $response,
                        'action' => 'client_like_wall'
                    ]));
                }
                break;

            case 'user_no_interesting':
                $this->noInteresting($userMessage->id);
                $from->send(json_encode([
                    'response' => $userMessage->id,
                    'action' => 'client_no_interesting'
                ]));
                break;

            case 'user_remove_comment':
                $this->removeComment($userMessage);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => [
                            'wall_id' => $userMessage->wall_id,
                            'comment_id' => $userMessage->comment_id
                        ],
                        'action' => 'client_remove_comment'
                    ]));
                }
                break;
            
            case 'user_update_comment':
                $comment = $this->updateComment($userMessage->comment);
                foreach ($this->users as $resourceId => $client) {
                    $client['connection']->send(json_encode([
                        'response' => $comment,
                        'action' => 'client_update_comment'
                    ]));
                }
                break;
            
            default:
                $this->onClose($from);
                break;
        }

        //$from->session->save();
    }

    public function onClose(ConnectionInterface $connection)
    {

        unset($this->users[$connection->resourceId]);

        printf("Connection %s has disconnected\n", $connection->resourceId);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        printf("An error has occurred: %s\n", $e->getMessage());

        $connection->close();
    }
}