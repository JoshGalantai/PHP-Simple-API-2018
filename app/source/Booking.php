<?php

namespace App\source;

use Exception;
use InvalidArgumentException;
use DateTime;

// Once we add more classes, we can extend a "base" class which will contain all shared logic
class Booking
{
    private $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    /***************************
     * PUBLIC FUNCTIONS (Validation happens here, these could be called from anywhere)
     ***************/

    // Delete booking by id
    public function delete_booking($id = 0) 
    {
        $this->_validate_int($id);

        return $this->_delete_booking($id);
    }

    // Get all bookings
    public function get_bookings() 
    {
        return $this->_get_bookings();
    }

    // Get bookings by id
    public function get_booking_by_id($id = 0) 
    {
        $this->_validate_int($id);

        return $this->_get_bookings($id);
    }

    // Create booking
    // Note:
        // If you wanted to be really diligent on validation, you would
        // ensure start date is always before end date and that newly
        // created bookings do not overlap with existing bookings
        // This could easily be done by NOT allowing inserts where:
            // new start < existing end AND new_end > existing start
        // In the interest of keeping this simple I am just doing basic
        // input validation
    public function create_booking($username = '', $reason = '', $start = '', $end = '') 
    {
        $this->_validate_username($username);
        $this->_validate_reason($reason);
        $this->_validate_datetime($start);
        $this->_validate_datetime($end);

        return $this->_create_booking($username, $reason, $start, $end);
    }

    // Update booking by ID fields are optional!
    // Note:
        // If you wanted to be really diligent on validation, you would
        // ensure start date is always before end date and that
        // updated bookings do not overlap with existing bookings
        // This could easily be done by NOT allowing updates where:
            // new start < existing end AND new_end > existing start
        // In the interest of keeping this simple I am just doing basic
        // input validation
    public function update_booking($id = 0, $username = '', $reason = '', $start = '', $end = '') 
    {
        $this->_validate_int($id);
        if ($username !== '') {
            $this->_validate_username($username);
        }
        if ($reason !== '') {
            $this->_validate_reason($reason);
        }
        if ($start !== '') {
            $this->_validate_datetime($start);
        }
        if ($end !== '') {
            $this->_validate_datetime($end);
        }

        // Check if nothing is being updated:
            // 1. Put params into array
            // 2. array_filter to remove all empty string values
            // 3. Check if remaining array is empty, if so, nothing being updated
        if (empty(array_filter(array($username, $reason, $start, $end)))) {
            throw new InvalidArgumentException;
        }

        return $this->_update_booking($id, $username, $reason, $start, $end);
    }

    /***************************
     * PRIVATE FUNCTIONS (Validation happened in public functions, just do stuff!)
     ***************/

    // ID present: return 1 record; No ID: return all!
    private function _get_bookings($id = 0) 
    {
        if ($id) {
            return (array) $this->db->table('bookings')->find($id);
        }
        else {
            // Could implement limit / offset if result set is really large
            $bookings = $this->db->table('bookings')->get()->toArray();
            return $this->_build_result_array($bookings);
        }
    }

    // Create booking fields are NOT optional!
    private function _create_booking($username, $reason, $start, $end) 
    {
        // Insert new value and get auto increment ID
        $id = $this->db->table('bookings')->insertGetId([
            'username' => $username,
            'reason' => $reason,
            'start' => $start,
            'end' => $end
        ]);

        // Good practice to return newly created record, not needed though
        return (array) $this->db->table('bookings')->find($id);
    }

    // Delete booking by ID
    private function _delete_booking($id) 
    {
        return $this->db->table('bookings')->where('id', $id)->delete();
    }

    // Update booking by ID fields are optional!
    private function _update_booking($id, $username = '', $reason = '', $start = '', $end = '') 
    {
        $update = array();

        if ($username) {
            $update['username'] = $username;
        }
        if ($reason) {
            $update['reason'] = $reason;
        }
        if ($start) {
            $update['start'] = $start;
        }
        if ($end) {
            $update['end'] = $end;
        }

        $this->db->table('bookings')->where('id', $id)->update($update);

        // Good practice to return updated record, not needed though
        return (array) $this->db->table('bookings')->find($id);
    }

    /***************************
     * HELPER FUNCTIONS (These could be in a separate helper class)
     ***************/

    // Build associative array with ID as index our of result object(s)
    private function _build_result_array($results) 
    {
        $result_array = array();
        foreach ($results as $result) {
            $result_array[$result->id] = (array) $result;
        }

        return $result_array;
    }

    // For our purposes a valid int is always unsigned and > 1
    private function _validate_int($int = 0) 
    {
        if (filter_var($int, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) === false) {
            Throw new InvalidArgumentException;
        }
    }

    // Username validation will be alphanumeric > 5 chars
    private function _validate_username($username = '') 
    {
        if (! preg_match('/^\w{5,}$/', $username)) {
            Throw new InvalidArgumentException;
        }
    }

    // Reason validation will be alphanumeric + horizontal spaces > 5 chars
    private function _validate_reason($reason = '') 
    {
        if (! preg_match('/^[\w|\h]{5,}$/', $reason)) {
            Throw new InvalidArgumentException;
        }
    }

    // Super simple date validation, attempt to convert string to datetime,
    // Then back to string, if result matches original string, it's valid!
    private function _validate_datetime($datetime = '') 
    {
        $valid_format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($valid_format, $datetime);
        if (! $date || $date->format($valid_format) !== $datetime) {
            Throw new InvalidArgumentException;
        }
    }
}
