<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Xmlrpc;

use DateTimeInterface;
use DateTime;

/**
 * Class Xmlrpc
 *
 * @package Blar\Xmlrpc
 */
class Xmlrpc {

    /**
     * @var array
     */
    protected static $defaultOptions = array(
        'output_type' => 'xml',
        'verbosity' => 'pretty',
        'version' => 'xmlrpc',
        'encoding' => 'UTF-8'
    );

    /**
     * @param array $defaultOptions
     * @return $this
     */
    public static function setDefaultOptions($defaultOptions) {
        self::$defaultOptions = $defaultOptions;
    }

    /**
     * @return array
     */
    public static function getDefaultOptions() {
        return self::$defaultOptions;
    }

    /**
     * @param mixed $value
     * @param string $type base64 or datetime
     * @return bool
     */
    public static function setType(&$value, $type) {
        xmlrpc_set_type($value, $type);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function getType($value) {
        return xmlrpc_get_type($value);
    }

    /**
     * @param mixed $values
     * @return mixed
     */
    public static function encodeValues($values) {
        array_walk_recursive($values, function(&$value) {

            if($value instanceof DateTimeInterface) {
                $value = $value->format(DateTime::ISO8601);
                Xmlrpc::setType($value, 'datetime');
            }

            if(!ctype_print($value)) {
                Xmlrpc::setType($value, 'base64');
            }

        });
        return $values;
    }

    /**
     * @param mixed $values
     * @return mixed
     */
    public static function decodeValues($values) {
        if(!is_array($values)) {
            return $values;
        }
        array_walk_recursive($values, function (&$value) {

            if(self::getType($value) == 'datetime') {
                $value = new DateTime($value->scalar);
            }

            if(self::getType($value) == 'base64') {
                $value = $value->scalar;
            }

        });
        return $values;
    }

    /**
     * @param array $value
     * @return bool
     */
    public static function isFault($value) {
        if(!is_array($value)) {
            return false;
        }
        return xmlrpc_is_fault($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function encode($value) {
        $value = self::encodeValues($value);
        return xmlrpc_encode($value);
    }

    /**
     * @param string $xml
     * @param array $options
     * @return mixed
     */
    public static function decode($xml, $options = array()) {
        $options += self::getDefaultOptions();
        $result = xmlrpc_decode($xml, $options['encoding']);
        return self::decodeValues($result);
    }

    /**
     * @param string $methodName
     * @param array $arguments
     * @param array $options
     * @return string
     */
    public static function encodeRequest($methodName, $arguments, $options = array()) {
        $options += self::getDefaultOptions();
        $arguments = self::encodeValues($arguments);
        return xmlrpc_encode_request($methodName, $arguments, $options);
    }

    /**
     * @param string $xml
     * @param string $methodName
     * @param array $options
     * @return mixed
     */
    public static function decodeRequest($xml, &$methodName, $options = array()) {
        $options += self::getDefaultOptions();
        $result = xmlrpc_decode_request($xml, $methodName, $options['encoding']);
        return self::decodeValues($result);
    }

    /**
     * @param array $arguments
     * @param array $options
     * @return string
     */
    public static function encodeResponse($arguments, $options = array()) {
        $options += self::getDefaultOptions();
        $arguments = self::encodeValues($arguments);
        return static::encodeRequest(NULL, $arguments, $options);
    }

    /**
     * @param string $xml
     * @param array $options
     * @return mixed
     */
    public static function decodeResponse($xml, $options = array()) {
        $options += self::getDefaultOptions();
        return static::decodeRequest($xml, $methodName, $options);
    }

}
