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

        <label>
            Request Type:
        </label>
        <br>
        <select id="request_type">
            <option value="GET">GET</option>
            <option value="PUT">PUT</option>
            <option value="POST">POST</option>
            <option value="DELETE">DELETE</option>
        </select>
        <br>
        <br>
        <label>
            URI:
        </label>
        <br>
        <input id="request_uri" type="text" value="/bookings">
        <br>
        <br>
        <label>
            Parameters:
        </label>
        <br>
        <textarea id="request_params" rows="5" style="width:80%;">username=johndoe</textarea>
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
