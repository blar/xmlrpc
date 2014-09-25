<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Xmlrpc;

use Exception;
use Blar\Http\HttpRequest;

/**
 * Class XmlrpcClient
 *
 * @package Blar\Xmlrpc
 */
class XmlrpcClient {

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param string $endpoint Endpoint Url
     */
    public function __construct($endpoint) {
        $this->setEndpoint($endpoint);
    }

    /**
     * @return string
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return \Blar\Http\HttpRequest
     */
    public function createRequest() {
        $request = new HttpRequest();
        $request->setMethod('POST');
        $request->getHeaders()->set('Content-Type', 'application/xml');
        $request->getHeaders()->set('Accept', 'application/xml, text/xml');
        return $request;
    }

    /**
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($methodName, $arguments) {
        if($this->getNamespace()) {
            $methodName = $this->getNamespace().'.'.$methodName;
        }
        $methodCall = Xmlrpc::encodeRequest($methodName, $arguments);

        $request = $this->createRequest();
        $request->setUrl($this->getEndpoint());
        $request->setBody($methodCall);
        $response = $request->send();

        $data = Xmlrpc::decodeResponse($response->getBody());

        if(Xmlrpc::isFault($data)) {
            throw new Exception(
                $data['faultString'],
                $data['faultCode']
            );
        }

        return $data;
    }

}
