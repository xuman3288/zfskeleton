<?php

namespace Test\Model;
use Zend\Captcha\AdapterInterface;
use Zend\Validator\Exception;


/**
 * Class Capt
 * @package Test\Model
 * @author Xuman
 * @version $Id$
 */
class Capt implements AdapterInterface
{

    /**
     * Generate a new captcha
     *
     * @return string new captcha ID
     */
    public function generate()
    {
        // TODO: Implement generate() method.
    }

    /**
     * Set captcha name
     *
     * @param  string $name
     * @return AdapterInterface
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
    }

    /**
     * Get captcha name
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * Get helper name to use when rendering this captcha type
     *
     * @return string
     */
    public function getHelperName()
    {
        // TODO: Implement getHelperName() method.
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        // TODO: Implement isValid() method.
    }

    /**
     * Returns an array of messages that explain why the most recent isValid()
     * call returned false. The array keys are validation failure message identifiers,
     * and the array values are the corresponding human-readable message strings.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * @return array
     */
    public function getMessages()
    {
        // TODO: Implement getMessages() method.
    }
}