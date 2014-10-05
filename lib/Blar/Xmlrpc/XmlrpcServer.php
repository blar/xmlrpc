<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Xmlrpc;

use Exception;

/**
 * Class XmlrpcServer
 *
 * @package Blar\Xmlrpc
 */
class XmlrpcServer {

    /**
     * @var resource
     */
    protected $handle;

    public function __construct() {
        $this->setHandle(xmlrpc_server_create());
    }

    public function __destruct() {
        xmlrpc_server_destroy($this->getHandle());
    }

    /**
     * @return mixed
     */
    public function getHandle() {
        return $this->handle;
    }

    /**
     * @param mixed $handle
     * @return $this
     */
    public function setHandle($handle) {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param string $methodName
     * @param Callable $callback
     * @return $this
     */
    public function registerMethod($methodName, $callback) {
        xmlrpc_server_register_method($this->getHandle(), $methodName, function() use($callback) {
            $input = func_get_args();
            $output = call_user_func_array($callback, Xmlrpc::decodeValues($input));
            return Xmlrpc::encodeValues($output);
        });
        return $this;
    }

    /**
     * @param string $xml
     * @return string
     */
    public function execute($xml = NULL) {
        if(is_null($xml)) {
            $xml = file_get_contents('php://input');
        }
        try {
            return xmlrpc_server_call_method($this->getHandle(), $xml, array(), Xmlrpc::getDefaultOptions());
        }
        catch(Exception $exception) {
            return Xmlrpc::encodeResponse(array(
                'faultCode' => $exception->getCode(),
                'faultString' => $exception->getMessage()
            ));
        }
    }

}
