<?php
require_once __DIR__.'/vendor/autoload.php';
Predis\Autoloader::register();

class RateLimit {
    private $redis;
    const RATE_LIMIT = 30; // allow 1 request every x seconds

    public function __construct() {
        $this->redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379
        ]);
    }

    public function getSleepTime($ip) {
        $value = $this->redis->get($ip);
        if(empty($value)) {

            $this->redis->set($ip, time()+self::RATE_LIMIT);
            return 0;
        } 
        $t = self::RATE_LIMIT - (time() - intval(strval($value))) - 60;
        if($t <= 0) {
            $this->redis->del($ip);
            return 0;
        } else {
            return $t;
        }
    }
}

