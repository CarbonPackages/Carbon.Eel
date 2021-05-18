<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\Exception;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

/**
 * @Flow\Proxy(false)
 */
class DateHelper implements ProtectedContextAwareInterface
{
    /**
     * Return seconds until the given offset.
     *
     * @param string offset in dateinerval format starting from midnight
     * @see: https://www.php.net/manual/en/dateinterval.format.php
     * @param boolean $dateinerval true if interval should be used or the $offset should be parsed
     * @throws Exception
     * @return int
     * 
     */
    public function secondsUntil(string $offset, $dateinerval = true): int
    {
        $now = new \DateTime();
        if ($dateinerval) {
            $then = new \DateTime();
            try {
                $interval = new \DateInterval($offset);
                $then
                    ->setTime(0, 0, 0)
                    ->add($interval);
            } catch (\Exception $exception) {
                throw new \Exception('Error while converting offset to DateInterval object.', 1621338829);
            }

            // if the end time is sooner than the start time we assume it's the next day
            if ($then->getTimestamp() < $now->getTimestamp()) {
                $then->add(new \DateInterval('P1D'));
            }
        } else {
            $then = new \DateTime($offset);
        }

        return $then->getTimestamp() - $now->getTimestamp();
    }

    /**
     * Convert time duration (1:00) into a DateInterval
     *
     * @param string $time
     * @return \DateInterval
     */
    public function timeToDateInterval(string $time): \DateInterval
    {
        $time = \explode(':', $time);
        return new \DateInterval("PT{$time[0]}H{$time[1]}M");
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
