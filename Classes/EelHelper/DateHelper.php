<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\Exception as EelException;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use DateInterval;
use DateTime;
use Exception;
use function explode;

/**
 * @Flow\Proxy(false)
 */
class DateHelper implements ProtectedContextAwareInterface
{
    /**
     * Return seconds until the given offset or datetime.
     *
     * @param string|DateTime $input datetime (string or object) or an offset in dateinerval format starting from midnight
     * @see: https://www.php.net/manual/en/dateinterval.format.php
     * @param boolean $dateinerval true if interval should be used or the $input should be parsed
     * @throws EelException
     * @return int
     *
     */
    public function secondsUntil(string|DateTime $input, $dateinerval = true): int
    {
        $now = new DateTime();
        if ($input instanceof DateTime) {
            $then = $input;
        } elseif ($dateinerval) {
            $then = new DateTime();
            try {
                $interval = new DateInterval($input);
                $then
                    ->setTime(0, 0, 0)
                    ->add($interval);
            } catch (Exception $exception) {
                throw new Exception(
                    'Error while converting offset to DateInterval object.',
                    1621338829
                );
            }

            // if the end time is sooner than the start time we assume it's the next day
            if ($then->getTimestamp() < $now->getTimestamp()) {
                $then->add(new DateInterval('P1D'));
            }
        } else {
            $then = new DateTime($input);
        }

        return $then->getTimestamp() - $now->getTimestamp();
    }

    /**
     * Convert time duration (1:00) into a DateInterval
     *
     * @param string $time
     * @return DateInterval
     */
    public function timeToDateInterval(string $time): DateInterval
    {
        $time = explode(':', $time);
        return new DateInterval("PT{$time[0]}H{$time[1]}M");
    }

    /**
     * All methods are considered safe, i.e. can be executed from within Eel
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
