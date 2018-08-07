<?php

require __DIR__ . '/../bootstrap/app.php';

$app->run();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Test Page
        </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/requests.js"></script>
    </head>

    <body>
        <hr>

        <label>
            <strong>
                Request
            </strong>
        </label>

        <hr>

        <br>
        <label>
            Action:
        </label>
        <br>
        <select id="action">
            <option value="GETALL">GET ALL BOOKINGS</option>
            <option value="POST">CREATE BOOKING</option>
            <option value="GET">GET BOOKING BY ID</option>
            <option value="PUT">UPDATE BOOKING BY ID</option>
            <option value="DELETE">DELETE BOOKING BY ID</option>
        </select>
        <br>
        <br>
        <label>
            ID:
        </label>
        <br>
        <input id="id" type="text" placeholder="1">
        <br>
        <br>
        <label>
            Username:
        </label>
        <br>
        <input id="username" type="text" placeholder="johndoe">
        <br>
        <br>
        <label>
            Reason:
        </label>
        <br>
        <input id="reason" type="text" placeholder="bronchitis">
        <br>
        <br>
        <label>
            Start:
        </label>
        <br>
        <input id="start" type="text" placeholder="2018-01-01 00:00:00">
        <br>
        <br>
        <label>
            End:
        </label>
        <br>
        <input id="end" type="text" placeholder="2018-01-01 00:00:00">
        <br>

        <hr>

        <label>
            <strong>
                Response
            </strong>
        </label>
        
        <hr>
        
        <textarea disabled id="response" rows="5" style="width:80%">
        </textarea>
        <br>
        <button id="submit">Submit</button>
        <br>
    </body>
</html>
