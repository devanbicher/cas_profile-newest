<?php

function get_basic_information($banner_connection, $lehigh_id){
    $info = [];

    $query_string = "select ADDRESS, EXTENSION, CHOSEN_NAME, CHOSEN_LASTNAME, JOB_TITLE, EMAIL from CAS_ROSTER_V where USERID= '".$lehigh_id."'";

    $oci_parse_obj = oci_parse($banner_connection, $query_string);

    if(oci_execute($oci_parse_obj)){
        //check if object is not empty
        //while($row = oci_fetch_assoc($oci_parse_obj)){
        $row = oci_fetch_assoc($oci_parse_obj);
        if ( empty($row)){
            $message = \Drupal::messenger();
            $message->addError('Banner did not return any information for: '.$lehigh_id);
        }   
        else{
            $address = addslashes($row['ADDRESS']);
            $exten = addslashes($row['EXTENSION']);
            $name = addslashes($row['CHOSEN_NAME']);
            $last_name = addslashes($row['CHOSEN_LASTNAME']);
            $job_title = addslashes($row['JOB_TITLE']);
            $email = addslashes($row['EMAIL']);

            $info = [$address, $exten, $name, $last_name, $job_title, $email];
        }
        
    }
    oci_free_statement($oci_parse_obj); 
    // is there a disconnect function or is this is?

    return $info;
}