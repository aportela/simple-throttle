<?php

namespace aportela\SimpleThrottle;

enum ThrottleDelayIncrementType
{
    /**
         * $msdelay *+= 2;
         */
    case MULTIPLY_BY_2;
    /**
         * $msdelay += 5000;
         */
    case INCREMENT_5_SECONDS;
    /**
         * $msdelay += 2000;
         */
    case INCREMENT_2_SECONDS;
    /**
         * $msdelay += 1000;
         */
    case INCREMENT_1_SECOND;
    /**
         * $msdelay += 500;
         */
    case INCREMENT_500_MILLISECONDS;
    /**
         * $msdelay += 200;
         */
    case INCREMENT_200_MILLISECONDS;
    /**
         * $msdelay += 100;
         */
    case INCREMENT_100_MILLISECONDS;
    /**
         * $msdelay += 50;
         */
    case INCREMENT_50_MILLISECONDS;
    /**
         * $msdelay += 20;
         */
    case INCREMENT_20_MILLISECONDS;
    /**
         * $msdelay += 10;
         */
    case INCREMENT_10_MILLISECONDS;
    /**
         * $msdelay += 5;
         */
    case INCREMENT_5_MILLISECONDS;
    /**
         * $msdelay += 2;
         */
    case INCREMENT_2_MILLISECONDS;
    /**
         * $msdelay += 1;
         */
    case INCREMENT_1_MILLISECOND;
}
