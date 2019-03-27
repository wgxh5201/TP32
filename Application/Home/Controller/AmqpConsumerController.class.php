<?php

namespace Home\Controller;

class AmqpConsumerController extends BaseController
{
    private $conn;
    CONST HOST = '127.0.0.1';
    CONST PORT = 5672;
    CONST USER = 'guest';
    CONST PASS = 'guest';
    CONST VHOST = '/';
    private $exchange = '/home/gaode/view';
    private $queue = 'request1';
    private $consumerTag = 'consumer';

    public function __construct()
    {
        if (!$this->conn)
            $this->conn = new \PhpAmqpLib\Connection\AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS, self::VHOST);
        parent::__construct();
    }


    //消费者
    public function consumer()
    {
        $connection = $this->conn;
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, \PhpAmqpLib\Exchange\AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange);
        //todo
        $callback = function($message)
        {
            echo " [x] Received ", $message->body, "\n";
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            // Send a message with the string "quit" to cancel the consumer.
            if ($message->body === 'quit') {
                $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
            }
        };
        $channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, $callback);

        $shutdown = function($channel, $connection)
        {
            $channel->close();
            $connection->close();
        };
        register_shutdown_function($shutdown, $channel, $connection);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}