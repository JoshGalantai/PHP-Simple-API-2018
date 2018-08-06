<?php

require __DIR__ . '/../vendor/autoload.php';

class BookingTest extends \PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $booking_id;

    // Note:
    // Super diligent unit testing would involve testing for errors
    // and exceptions through various inputs and so forth, for the sake
    // of simplicity I will just be testing each function for success

    // PHP Unit runs this first!
    public static function setUpBeforeClass()
    {
        self::$db = new \Illuminate\Database\Capsule\Manager;
        $db = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'mysql',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ];

        self::$db->addConnection($db);
        self::$db->setAsGlobal();
        self::$db->bootEloquent();
    }

    // PHP Unit runs this last!
    public static function tearDownAfterClass()
    {
        self::$db = null;
    }

    public function test_create_booking()
    {
        $booking = new \App\source\Booking(self::$db);
        $test = array(
            'username' => 'johndoe',
            'reason' => 'bronchitis',
            'start' => '2018-01-01 12:00:00',
            'end' => '2018-01-01 13:00:00'
        );
        $result = $booking->create_booking(
            $test['username'],
            $test['reason'],
            $test['start'],
            $test['end']
        );

        // Make sure record created is same as passed in
        $this->assertEquals($result['username'], $test['username']);
        $this->assertEquals($result['reason'], $test['reason']);
        $this->assertEquals($result['start'], $test['start']);
        $this->assertEquals($result['end'], $test['end']);

        // Save ID to be used in update, get and delete tests
        self::$booking_id = $result['id'];
    }

    public function test_update_booking()
    {
        $booking = new \App\source\Booking(self::$db);
        $test = array(
            'start' => '2018-01-01 13:00:00',
            'end' => '2018-01-01 14:00:00'
        );
        $result = $booking->update_booking(
            self::$booking_id,
            '',
            '',
            $test['start'],
            $test['end']
        );

        // Make sure result equals values passed in!
        $this->assertEquals($result['start'], $test['start']);
        $this->assertEquals($result['end'], $test['end']);

        // Make sure username / reason are unchanged
        $this->assertEquals($result['username'], 'johndoe');
        $this->assertEquals($result['reason'], 'bronchitis');
    }

    public function test_get_booking()
    {
        $booking = new \App\source\Booking(self::$db);
        $result = $booking->get_booking_by_id(
            self::$booking_id
        );

        // Make sure values match the ones that were created / updated
        $this->assertEquals($result['username'], 'johndoe');
        $this->assertEquals($result['reason'], 'bronchitis');
        $this->assertEquals($result['start'], '2018-01-01 13:00:00');
        $this->assertEquals($result['end'], '2018-01-01 14:00:00');
    }

    public function test_delete_booking()
    {
        $booking = new \App\source\Booking(self::$db);
        $result = $booking->delete_booking(
            self::$booking_id
        );

        // Make sure a record was deleted
        $this->assertEquals($result, true);
    }
}
