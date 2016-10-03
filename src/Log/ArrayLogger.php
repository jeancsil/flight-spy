<?php
/**
 * @author Jean Silva <me@jeancsil>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Log;

use Monolog\Logger;
use Psr\Log\LoggerAwareTrait;

class ArrayLogger extends Logger
{
    const COLOR_NONE = "[0m";
    const COLOR_RED = "[31m";
    const COLOR_GREEN = "[32m";
    const COLOR_YELLOW = "[33m";

    public function debug($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::debug($this->format($item), $context);
        }
    }

    public function info($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::info($this->format($item), $context);
        }
    }

    public function notice($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::notice($this->format($item), $context);
        }
    }

    public function warn($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::warn($this->format($item), $context);
        }
    }

    public function error($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::error($this->format($item, self::COLOR_RED), $context);
        }
    }

    public function critical($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::critical($this->format($item, self::COLOR_YELLOW), $context);
        }
    }

    public function alert($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::alert($this->format($item), $context);
        }
    }

    public function emergency($items, array $context = [])
    {
        foreach ((array) $items as $item) {
            parent::emergency($this->format($item), $context);
        }
    }

    private function format($message, $color = self::COLOR_GREEN)
    {
        return chr(27) . "$color$message" . chr(27) . self::COLOR_NONE;
    }
}
