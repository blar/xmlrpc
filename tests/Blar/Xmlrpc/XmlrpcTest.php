<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

use Blar\Xmlrpc\Xmlrpc;
use PHPUnit_Framework_TestCase as TestCase;

class XmlrpcTest extends TestCase {

    public function testDateTime() {
        $encoded = Xmlrpc::encodeRequest('formatDateTime', array(
            'from' => new DateTime('2014-01-01'),
            'until' => new DateTime('2014-12-31')
        ));
        $decoded = Xmlrpc::decodeRequest($encoded, $methodName);
        $this->assertEquals('DateTime', get_class($decoded[0]['from']));
        $this->assertEquals('DateTime', get_class($decoded[0]['until']));
    }

}
