<?php

namespace App\Console\Commands;


use App\Traits\AlbumTrait;
use Illuminate\Console\Command;
use Kunnu\RabbitMQ\RabbitMQQueue;
use Kunnu\RabbitMQ\RabbitMQExchange;
use Kunnu\RabbitMQ\RabbitMQIncomingMessage;
use Kunnu\RabbitMQ\RabbitMQGenericMessageConsumer;

class MyRabbitConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:auth-consumer {--exchange=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My consumer command';

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
        $rabbitMQ = app('rabbitmq');
        $messageConsumer = new RabbitMQGenericMessageConsumer(
            function (RabbitMQIncomingMessage $message) {

            },
            $this, // Scope the closure to the command
        );

        $routingKey = 'auth';
        $queue = new RabbitMQQueue('auth_queue', ['declare' => true]);
        $exchange = new RabbitMQExchange($this->option('exchange') ?? '', ['declare' => true]);

        $messageConsumer
            ->setExchange($exchange)
            ->setQueue($queue);

        $rabbitMQ->consumer()->consume($messageConsumer, $routingKey);
    }
}
