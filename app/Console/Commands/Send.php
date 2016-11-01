<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $socket->send(json_encode([
                'category' => 'kittensCategory',
                'title' => 'title data',
                'article' => 'article data',
                'when' => time()
            ]));
            //dd($socket);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
