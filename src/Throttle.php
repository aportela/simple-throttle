<?php

namespace aportela\SimpleThrottle;

class Throttle
{
    private int $originalThrottleDelayMS = 0;
    private int $currentThrottleDelayMS = 0;
    private int $lastThrottleTimestamp = 0;
    private int $maxThrottleDelayMS = 0;
    private int $throttleUsleepMS = 10;

    public function __construct(protected \Psr\Log\LoggerInterface $logger, int $throttleDelayMS, int $maxThrottleDelayMS, int $throttleUsleepMS = 10)
    {
        if ($throttleDelayMS < 1) {
            $this->logger->error("\aportela\SimpleThrottle\Throttle::__construct error: throttleDelayMS value must be >= 1", [$throttleDelayMS, $maxThrottleDelayMS]);
            throw new \InvalidArgumentException("throttleDelayMS param value must be >= 1");
        }
        if ($maxThrottleDelayMS <= $throttleDelayMS) {
            $this->logger->error("\aportela\SimpleThrottle\Throttle::__construct error: maxThrottleDelayMS value must be > {$throttleDelayMS}", [$throttleDelayMS, $maxThrottleDelayMS]);
            throw new \InvalidArgumentException("maxThrottleDelayMS param value must be > {$throttleDelayMS}");
        }
        if ($throttleUsleepMS < 1) {
            $this->logger->error("\aportela\SimpleThrottle\Throttle::__construct error: throttleUsleepMS value must be >= 1", [$throttleDelayMS, $maxThrottleDelayMS]);
            throw new \InvalidArgumentException("throttleUsleepMS param value must be >= 1");
        }
        $this->originalThrottleDelayMS = $throttleDelayMS;
        $this->currentThrottleDelayMS = $throttleDelayMS;
        $this->maxThrottleDelayMS = $maxThrottleDelayMS;
        $this->throttleUsleepMS = $throttleUsleepMS;
        $this->lastThrottleTimestamp = intval(microtime(true) * 1000);
    }

    public function __destruct() {}

    /**
     * increment throttle ms delay
     */
    public function increment(\aportela\SimpleThrottle\ThrottleDelayIncrementType $incrementType = \aportela\SimpleThrottle\ThrottleDelayIncrementType::MULTIPLY_BY_2): void
    {
        $this->logger->debug("\aportela\SimpleThrottle\Throttle::incrementThrottle");
        if ($this->currentThrottleDelayMS != $this->maxThrottleDelayMS) {
            $increment = 0;
            switch ($incrementType) {
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::MULTIPLY_BY_2:
                    $increment = $this->currentThrottleDelayMS * 2;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_5_SECONDS:
                    $increment = 5000;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_2_SECONDS:
                    $increment = 2000;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_1_SECOND:
                    $increment = 1000;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_500_MILLISECONDS:
                    $increment = 500;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_200_MILLISECONDS:
                    $increment = 200;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_100_MILLISECONDS:
                    $increment = 100;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_50_MILLISECONDS:
                    $increment = 50;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_20_MILLISECONDS:
                    $increment = 20;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_10_MILLISECONDS:
                    $increment = 10;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_5_MILLISECONDS:
                    $increment =  5;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_2_MILLISECONDS:
                    $increment =  2;
                    break;
                case \aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_1_MILLISECOND:
                    $increment =  1;
                    break;
            }
            if ($this->currentThrottleDelayMS + $increment < $this->maxThrottleDelayMS) {
                $this->currentThrottleDelayMS += $increment;
            } else {
                $this->currentThrottleDelayMS = $this->maxThrottleDelayMS;
            }
        } else {
            $this->logger->notice("\aportela\SimpleThrottle\Throttle::incrementThrottle reached maxThrottleDelayMS");
        }
    }

    /**
     * reset throttle to original value
     */
    public function reset(): void
    {
        $this->currentThrottleDelayMS = $this->originalThrottleDelayMS;
    }

    /**
     * wait until reach throttle delay from last call
     */
    public function throttle(): void
    {
        if ($this->currentThrottleDelayMS > 0) {
            $currentTimestamp = intval(microtime(true) * 1000);
            while (($currentTimestamp - $this->lastThrottleTimestamp) < $this->currentThrottleDelayMS) {
                usleep($this->throttleUsleepMS);
                $currentTimestamp = intval(microtime(true) * 1000);
            }
            $this->lastThrottleTimestamp = $currentTimestamp;
        }
    }
}
