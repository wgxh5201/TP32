<?php

namespace Home\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class AmqpPublishController extends BaseController
{
    private $conn;
    CONST HOST = '127.0.0.1';
    CONST PORT = 5672;
    CONST USER = 'guest';
    CONST PASS = 'guest';
    CONST VHOST = 'htdocs';
    private $exchange = '/home/gaode/view';
    private $queue = 'request1';

    public function __construct()
    {
        $this->conn = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS, self::VHOST);
        parent::__construct();
    }

    //生产者
    public function publisher()
    {
        $argv = I('argv');
        $channel = $this->conn->channel();
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange);

//        $messageBody = implode(' ', array_slice($argv, 1));
        $message = new AMQPMessage($argv, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $this->exchange);

        $channel->close();
        $this->conn->close();
    }
}