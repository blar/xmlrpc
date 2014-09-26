<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

use Blar\Xmlrpc\XmlrpcClient;
use PHPUnit_Framework_TestCase as TestCase;

class XmlrpcClientTest extends TestCase {

    public function testListMethods() {
        $client = new XmlrpcClient('http://blar.wordpress.com/xmlrpc.php');
        $client->setNamespace('system');
        $response = $client->listMethods();
        $this->assertEquals('array', gettype($response));
    }

}
