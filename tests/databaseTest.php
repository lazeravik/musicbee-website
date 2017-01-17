<?php
require_once $_SERVER['DOCUMENT_ROOT'].'app/config.php';

use App\Lib\Database as db;
use App\Lib\MBReleaseManager;
use App\Lib\MBReleaseType;

class databaseTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function test_database_connection()
    {
        $this->assertEquals(true, db::isDatabaseConnected(), "Database connection is working properly!");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_musicbeerelease_stable()
    {
        $this->expectException(
            MBReleaseManager::getMusicBeeRelease(
                "uselessstuff",
                db::getDatabaseConnection()
            )
        );
    }
}
