<?php

use Drupal\node\Entity\Node;

require 'banner/bannerquerying.php';
require 'banner/bannerconnection.php';

function getNidFromLehighId($lehigh_id){
    $profile_query = Drupal::entityQuery('node')
        ->condition('status',1)
        ->condition('type','cas_profile')
        ->condition('field_lehigh_id',$lehigh_id,'=')
        ->execute();

    if(empty($profile_query)){
        $profile_id = 'ERROR-NO-PROFILE';
    }
    else{
        $profile_id = array_values($profile_query)[0];
    }

    return $profile_id;
}

function fetchBannerInfo($bann_conn, $lehigh_id){
    $banner_basic_info = get_basic_information($bann_conn, $lehigh_id);
    
    //some sort of error checking
    return $banner_basic_info;
}

function compareUpdateProfile($info, $nid){
    // ONE CONSIDERATION, is that this should also create a new revision when making this update and I am not sure if that does it automatically.
    if ( empty($info) ){
        // a message
        $message = \Drupal::messenger();
        $message->addError(['#markup' => 'Could NOT find any associated info in banner for this node: <a href="/node/'.$nid.'">for this node</a>.']);
    }
    else{
        $node = Node::load($nid);
        // This is the information stored in $info
        //[$address, $exten, $name, $last_name, $job_title, $email];

        $updated = FALSE;

        //address
        if ( $node->get('field_office')->getValue()[0]['value'] != $info[0] ){
            $node->set('field_office',$info[0]);
            $updated = TRUE;
        }
        //telephone
        if ( $node->get('field_telephone')->getValue()[0]['value'] != $info[1] ){
            $node->set('field_telephone',$info[1]);
            $updated = TRUE;
        }
        // title, aka full name seems like a special case, I won't add that in here.

        // Not sure if this should be updated or not, seems like maybe in case some one changes their last name
        if ( $node->get('field_last_name')->getValue()[0]['value'] != $info[3] ){
            $node->set('field_last_name',$info[3]);
            $updated = TRUE;
        }

        // this one will need to be update in the future to iteration through the values in this field once the situation with the positions is figured out.
        if ( $node->get('field_positions')->getValue()[0]['value'] != $info[4] ){
            $node->set('field_positions',$info[4]);
            $updated = TRUE;
        }
        // not sure if this field will be customizable or not, so for now it is just going to be updated to their current primary posisiton title
        // if ( $node->get('field_flair')->getValue()[0]['value'] != $info[4] ){
        //     $node->set('field_flair',$info[4]);
        //     $updated = TRUE;
        // }
        //email shouldn't change. 

        //not sure if I want to pass the $updated variable back so that a message can be created from another function, for now it is not.
        if ($updated){
            $request_time = \Drupal::time()->getRequestTime();
            $title = $node->get('title')->getValue()[0]['value'];
            $message = \Drupal::messenger();
            $message->addMessage(['#markup' => 'Profile <a href="/node/'.$nid.'">'.$title.'</a> has been updated.']);
            // Make update a new revision
            $node->setNewRevision(TRUE);
            $node->revision_log = 'Created banner update revision for node ' . $nid . ', ' . $title;
            $node->setRevisionCreationTime($request_time);
            $node->save();
        }
    }   
    
}

function singleProfileUpdate($lehigh_id){
    //get/check that the profile with that lehigh id exists
    $profile_id = getNidFromLehighId($lehigh_id);

    if ($profile_id == 'ERROR-NO-PROFILE'){
        //check if that user exists and make a different error depending on whether that user exists

        $message = \Drupal::messenger();
        $message->addError('Could NOT find a profile with the Lehigh User ID:  '.$lehigh_id);
    }
    else{
        //create the db connection
        $bann_conn = banner_connect();
        
        $banner_info = fetchBannerInfo($bann_conn, $lehigh_id);
        
        compareUpdateProfile($banner_info, $profile_id);

    }
}

// fucntion to get lehigh_id from node id, ideally without loading the node fully i guess
function getLehighIdFromNid($nid){
    $node = Node::load($nid);
    
    $lehigh_id = $node->get('field_lehigh_id')->getValue()[0]['value'];

    return $lehigh_id;
}

// function that loops through all profiles and updates them

function updateAllProfiles(){
    $profile_query = Drupal::entityQuery('node')
        ->condition('status',1)
        ->condition('type','cas_profile')
        ->exists('field_lehigh_id')
        ->execute();

    $bann_conn = banner_connect();

    foreach($profile_query as $key => $pid){

        $lehigh_id = getLehighIdFromNid($pid); // get lehigh id for banner querying

        $banner_info = fetchBannerInfo($bann_conn, $lehigh_id); //get the info from banner
        
        compareUpdateProfile($banner_info, $pid);
        
    }

}