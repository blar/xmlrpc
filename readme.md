[![Build Status](https://travis-ci.org/blar/xmlrpc.png?branch=master)](https://travis-ci.org/blar/xmlrpc)
[![Coverage Status](https://coveralls.io/repos/blar/xmlrpc/badge.png?branch=master)](https://coveralls.io/r/blar/xmlrpc?branch=master)
[![Dependency Status](https://gemnasium.com/blar/xmlrpc.svg)](https://gemnasium.com/blar/xmlrpc)
[![Dependencies Status](https://depending.in/blar/xmlrpc.png)](http://depending.in/blar/xmlrpc)

# OOP-Wrapper für XML-RPC

## Methode aufrufen

    $client = new XmlrpcClient('http://blar.wordpress.com/xmlrpc.php');
    $client->setNamespace('system');
    $response = $client->listMethods();

