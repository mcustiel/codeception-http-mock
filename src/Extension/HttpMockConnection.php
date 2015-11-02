<?php
namespace Codeception\Extension;

use InterNations\Component\HttpMock\Server;

class HttpMockConnection
{
    /**
     * @var \InterNations\Component\HttpMock\Server
     */
    private static $connection;

    /**
     * @param \InterNations\Component\HttpMock\Server $connection
     */
    public static function setConnection(Server $connection)
    {
        self::$connection = $connection;
    }

    /**
     * @return \InterNations\Component\HttpMock\Server
     */
    public static function get()
    {
        return self::$connection;
    }
}
