<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Validator;

interface ValidatorInterface
{
    /**
     * @param $instance
     * @return \Jeancsil\FlightSpy\Validator\ValidatorInterface
     */
    public function setTarget($instance);

    /**
     * @throw ValidationException
     * @return void
     */
    public function validate();
}
