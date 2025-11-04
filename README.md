# simple-throttle

This is a simple approach to implement exponential backoff for retrying operations that may fail intermittently, such as network requests (API calls?) or file access. While there are more robust and optimized solutions out there, this is just a lightweight strategy I use in some of my personal projects when I need to handle temporary failures without overcomplicating things. It’s not meant for production-grade systems, just a basic solution for my use cases.

## Requirements

- mininum php version 8.4

## Install (composer) dependencies:

```Shell
composer require aportela/simple-throttle
```

## Code example:

```php
<?php

    require "vendor/autoload.php";

    $logger = new \Psr\Log\NullLogger("");

    $throttle = new \aportela\SimpleThrottle\Throttle($logger, 500, 5000);

    for ($i = 0; $i < 32; $i++) {
        // throttle default time between iterations
        $throttle->throttle();
        // instead of this dummy code block, could you make a call to a remote API here that could fail due to a rate limit
        $failed = (bool) mt_rand(0, 1);
        if (! $failed) {
            echo "Success... reseting throttle to default" . PHP_EOL;
            // on interation success reset throttle to default time
            $throttle->reset();
        } else {
            echo "Error... incrementing throttle" . PHP_EOL;
            // on interation error increment throttle ( ms = ms + 500 ms)
            $throttle->increment(\aportela\SimpleThrottle\ThrottleDelayIncrementType::INCREMENT_500_MILLISECONDS);
        }
    }
```

![PHP Composer](https://github.com/aportela/simple-fs-cache/actions/workflows/php.yml/badge.svg)
