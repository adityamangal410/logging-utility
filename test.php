<?php

$myFile = "data.js";
$fh = fopen($myFile, 'a') or die("can't open file");
$stringData = "YAHOO.example.Data = {
    areacodes: [
        {areacode: '201', state: 'New Jersey'},
        {areacode: '202', state: 'Washington, DC'},
        {areacode: '203', state: 'Connecticut'},
        {areacode: '204', state: 'Manitoba, Canada'},
        {areacode: '205', state: 'Alabama'},
        {areacode: '206', state: 'Washington'},
        {areacode: '207', state: 'Maine'}
  ]
};";
fwrite($fh, $stringData);
fclose($fh);

?>