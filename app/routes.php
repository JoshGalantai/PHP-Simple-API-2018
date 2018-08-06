<?php

// Groups help organize your routes, helps keep clean code!
$app->group(
    '/bookings', function () use ($app, $booking) {

        // Get all bookings (Here you would implement filters on specific fields)
        $app->get(
            '', function ($request, $response) use ($booking, $app) {
                try {
                    return json_encode($booking->get_bookings());
                }
                catch(InvalidArgumentException  $e) {
                    return 'Unable to get all bookings, invalid input!';
                }
                catch(Exception $e) {
                    return 'Unable to get all bookings, an error occurred!';
                }
            }
        );

        // Create booking
        $app->post(
            '', function ($request, $response) use ($booking, $app) {
                $username = $request->getQueryParam('username', '');
                $reason = $request->getQueryParam('reason', '');
                $start = $request->getQueryParam('start', '');
                $end = $request->getQueryParam('end', '');

                try {
                    return json_encode($booking->create_booking($username, $reason, $start, $end));
                }
                catch(\InvalidArgumentException $e) {
                    return 'Unable to get booking by id, invalid input!';
                }
                catch(\Exception $e) {
                    return 'Unable to get all bookings, an error occurred!';
                }
            }
        );

        // /bookings/booking_id group (Also initial validation for booking_id)
        // I also included input validation in booking class for good practice
        // because they are public functions. They could be called from a script
        $app->group(
            '/{booking_id:[0-9]+}', function () use ($app, $booking) {

                // Get booking by ID
                $app->get(
                    '', function ($request, $response, $args) use ($booking, $app) {
                        try {
                            return json_encode($booking->get_booking_by_id($args['booking_id']));
                        }
                        catch(\InvalidArgumentException $e) {
                            return 'Unable to get booking by id, invalid input!';
                        }
                        catch(\Exception $e) {
                            return 'Unable to get all bookings, an error occurred!';
                        }
                    }
                );

                // Update booking by ID
                $app->put(
                    '', function ($request, $response, $args) use ($booking, $app) {
                        $username = $request->getQueryParam('username', '');
                        $reason = $request->getQueryParam('reason', '');
                        $start = $request->getQueryParam('start', '');
                        $end = $request->getQueryParam('end', '');

                        try {
                            return json_encode($booking->update_booking($args['booking_id'], $username, $reason, $start, $end));
                        }
                        catch(\InvalidArgumentException $e) {
                            return 'Unable to get booking by id, invalid input!';
                        }
                        catch(\Exception $e) {
                            return 'Unable to get all bookings, an error occurred!';
                        }
                    }
                );

                // Delete booking by ID
                $app->delete(
                    '', function ($request, $response, $args) use ($booking, $app) {
                        try {
                            return $booking->delete_booking($args['booking_id']) ? 'Success' : 'Nothing to delete';
                        }
                        catch(\InvalidArgumentException $e) {
                            return 'Unable to get booking by id, invalid input!';
                        }
                        catch(\Exception $e) {
                            return 'Unable to get all bookings, an error occurred!';
                        }
                    }
                );
            }
        );
    }
);
