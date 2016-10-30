<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Helpers\JSocket;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class RunSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run web socket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('run ws');
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new JSocket\JSocket()
                )
            ), 8082
        );

        $server->run();
    }
}