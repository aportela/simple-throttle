<?php

declare(strict_types=1);

namespace aportela\SimpleThrottle\Test;

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

final class ThrottleTest extends \aportela\SimpleThrottle\Test\BaseTest
{
    private const int MAX_MS_TIME_LAG = 50;

    /**
     * Called once just like normal constructor
     */
    #[\Override]
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public function testSimple(): void
    {
        $throttle = new \aportela\SimpleThrottle\Throttle(self::$logger, 200, 5000, 10);
        $start = intval(microtime(true) * 1000);
        $throttle->throttle();
        $end = intval(microtime(true) * 1000);
        $total = ($end - $start);
        $this->assertTrue($total >= 200);
        if ($total > 200) {
            $this->assertTrue(($total - 200) < self::MAX_MS_TIME_LAG);
        }
    }

    public function testIncrementing2Seconds(): void
    {
        $throttle = new \aportela\SimpleThrottle\Throttle(self::$logger, 1000, 5000, 10);
        // 1000 ms by default + 2 second increment = 3000
        $throttle->increment(\aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_2_SECONDS);

        $start = intval(microtime(true) * 1000);
        $throttle->throttle();
        $end = intval(microtime(true) * 1000);
        $total = ($end - $start);
        $this->assertTrue($total >= 3000);
        if ($total > 3000) {
            $this->assertTrue(($total - 3000) < self::MAX_MS_TIME_LAG);
        }
    }

    public function testResetingToDefault(): void
    {
        $throttle = new \aportela\SimpleThrottle\Throttle(self::$logger, 500, 10000, 10);
        // 500 ms by default + 5 second increment = 5500
        $throttle->increment(\aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_5_SECONDS);
        // reset to default (500 ms)
        $throttle->reset();

        $start = intval(microtime(true) * 1000);
        $throttle->throttle();
        $end = intval(microtime(true) * 1000);
        $total = ($end - $start);
        $this->assertTrue($total >= 500);
        if ($total > 500) {
            $this->assertTrue(($total - 500) < self::MAX_MS_TIME_LAG);
        }
    }
}
