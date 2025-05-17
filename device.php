<?php
function getDeviceID() {
    if (!isset($_COOKIE['device_id'])) {
      
        $deviceID = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        setcookie('device_id', $deviceID, time() + (86400 * 30), "/"); 
    } else {
        $deviceID = $_COOKIE['device_id'];
    }
    return $deviceID;
}
