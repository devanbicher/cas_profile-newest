<?php

require 'addCasProfile.php';

$new_profile_id = $_SERVER['argv'][5];

echo('Adding: '.$new_profile_id."\n");

//phpinfo();

add_new_profile($new_profile_id);

echo("if the function above worked add some better output here. \n ");
