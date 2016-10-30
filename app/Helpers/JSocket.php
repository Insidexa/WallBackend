<?php

namespace Helpers\JSocket;

use App;
use Auth;
use Config;
use Crypt;
use App\User;
use Faker\Provider\Image;
use Illuminate\Session\SessionManager;

use App\Like;
use App\Wall;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

function lc($text)
{
    echo $text . PHP_EOL;
}

class JSocket implements MessageComponentInterface
{

    /**
     * @var array
     */
    protected $users;
    protected $walls;

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
        $this->walls = [];

        $this->initWalls();
    }

    protected function initWalls()
    {
        $this->walls = Wall::all();
        lc('walls');
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
        $like = Like::whereTypeId($data->type_id)
            ->whereUserId($data->user_id)
            ->whereType($data->type);

        if ($like->get()->count() == 0) {
            Like::create([
                'type_id' => $data->type_id,
                'user_id' => $data->user_id,
                'type' => $data->type
            ]);
            $action = true;
        } else {
            $like->delete();
            $action = false;
        }

        return [
            'count' => $like
                ->get()
                ->count(),
            'type' => $data->type,
            'action' => $action,
            'type_id' => $data->type_id,
            'wall_id' => $data->wall_id
        ];
    }

    protected function removeWall($id) {
        Wall::find($id)->delete();
    }

    protected function addWall ($wallData) {
        
        $wall = Wall::create([
            'user_id' => 1,
            'text' => $wallData->text
        ]);
        $images = [];

        foreach($wallData->images as $image) {
            $images[] = [
                'path' => \Helpers\ImageFromBase64::convertAndSave($image->image),
                'wall_id' => $wall->id,
                'name' => ''
            ];
        }
        
        \App\Image::insert($images);
        
        return $wall->whereId($wall->id)->with('user')->with('images')->with('comments')->first();
    }

    protected function addComment($commentData) {
        
        $parentId = 0;
        
        if ($commentData->comment->parent_id)
            $parentId = $commentData->comment->parent_id;
        
        return \App\Comment::create([
            'user_id' => 1,
            'wall_id' => $commentData->wall_id,
            'text' => $commentData->comment->text,
            'parent_id' => $parentId
        ]);
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