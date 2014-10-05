[![Build Status](https://travis-ci.org/blar/xmlrpc.png?branch=master)](https://travis-ci.org/blar/xmlrpc)
[![Coverage Status](https://coveralls.io/repos/blar/xmlrpc/badge.png?branch=master)](https://coveralls.io/r/blar/xmlrpc?branch=master)
[![Dependency Status](https://gemnasium.com/blar/xmlrpc.svg)](https://gemnasium.com/blar/xmlrpc)
[![Dependencies Status](https://depending.in/blar/xmlrpc.png)](http://depending.in/blar/xmlrpc)

# OOP-Wrapper für XML-RPC

## Beispiele für den Client

### Methode aufrufen

    // Endpunkt für die Aufrufe
    $client = new XmlrpcClient('http://blar.wordpress.com/xmlrpc.php');

    // Namespace für die Methodenaufrufe festlegen
    $client->setNamespace('system');

    // Method aufrufen ("system.listMethods")
    $response = $client->listMethods();

### Entwurf über die Blogger-API in einem Wordpress-Blog erstellen

    $client = new XmlrpcClient('http://blar.wordpress.com/xmlrpc.php');
    $client->setNamespace('blogger');
    $postId = $client->newPost(NULL, NULL, $userName, $password, 'Hello World', false);

### Entwurf über die MetaWeblog-API in einem Wordpress-Blog erstellen

    $client = new XmlrpcClient('http://blar.wordpress.com/xmlrpc.php');
    $client->setNamespace('metaWeblog');
    $content = array(
        'title' => 'foo',
        'description' => 'bar',
        'dateCreated => new DateTime('2014-09-29 13:37')
    );
    $postId = $client->newPost(NULL, $userName, $password, $content, false);

Variablen vom Typ **DateTime** werden automatisch ohne Konvertierung korrekt per XML-RPC übertragen.

## Beispiele für den Server

### Einfacher Server 

    $server = new XmlrpcServer();
    $server->registerMethod('blogger.getRecentPosts', function() {
        return array(
            array(
                'postid' => 1,
                'user_id' => 1,
                'dateCreated' => new DateTime(),
                'content' => 'foobar 1'
            ),
            array(
                'postid' => 2,
                'user_id' => 1,
                'dateCreated' => new DateTime(),
                'content' => 'foobar 2'
            )
        );
    });
    echo $server->execute();

### Exceptions in der Methode

In der Methode können Exceptions geworfen werden, die automatisch in Fehlermeldungen von XML-RPC umgewandelt werden.

    $server = new XmlrpcServer();
    $server->registerMethod('blogger.getRecentPosts', function() {
        throw new Exception('foo bar', 1337);
    });
    echo $server->execute();
