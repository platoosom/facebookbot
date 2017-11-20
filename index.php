<?php

$access_token = 'EAAFUz3HsNTEBAMqga01OFRSuyyaBg3fCGXrmAkOOMZB1Y4paA5JcHuG1AYyEUsp7xFKoEx0xgEfhLYaGa9ixxj6H6eQbuQsNTdK0GNH3Rc1kNXvAgZANVn1IZCz8pfBP6iljsbwvgPq7rfT08c48z2eDRXeKzXbxTZBl3SkhAwZDZD';

/* validate verify token needed for setting up web hook */ 

if (isset($_GET['hub_verify_token'])) { 
    if ($_GET['hub_verify_token'] === $access_token) {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}
