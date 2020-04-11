<?php
require_once __DIR__.'/vendor/autoload.php';
Predis\Autoloader::register();

class RateLimit {
    private $redis;
    const RATE_LIMIT_SECS = 60; // allow 1 request every x seconds

    public function __construct() {
        $this->redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'localhost', // or the server IP on which Redix is running
            'port'   => 6380
        ]);
    }

    /**
     * Returns the number of seconds to wait until the next time the IP is allowed
     * @param ip {String}
     */
    public function getSleepTime($ip) {
        $value = $this->redis->get($ip);
        if(empty($value)) {
            // if the key doesn't exists, we insert it with the current datetime, and an expiration in seconds
            $this->redis->set($ip, time(), self::RATE_LIMIT_SECS*1000);
            return 0;
        } 
        return self::RATE_LIMIT_SECS - (time() - intval(strval($value)));
    } // getSleepTime

} // class RateLimit
