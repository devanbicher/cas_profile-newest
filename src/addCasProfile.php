<?php

use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;

function add_new_profile($lehigh_user){

    $user_query = Drupal::entityQuery('user')
      ->condition('name',$lehigh_user,'=')
      ->execute();

    if(empty($user_query)){
      // User doesn't exist, add that new user
      $new_user = User::create([
        'name' => $lehigh_user,
        'pass' => '',
      ]);
      $new_user->roles[]='faculty';
      $new_user->activate();
      $new_user->save();

      $author_id = $new_user->id();
    }
    else{
      //user exists, assign them the faculty role
      $user_id = array_values($user_query)[0];
      $edit_user = User::load($user_id);

      $edit_user->roles[]='faculty';
      $edit_user->activate();
      $edit_user->save();

      $message = \Drupal::messenger();
      $message->addWarning(['#markup' => '<a href="/user/'.$user_id.'"> User: '.$lehigh_user.' </a> Already exists, activating them and assigning the faculty role.']);
      
      $author_id = $user_id;
    }
    
    // add new profile
    $new_profile = Node::create([
        'type' => 'cas_profile',
        'title' => $lehigh_user.'- TEMP Initial title',
        'field_lehigh_id' => $lehigh_user,
      ]);
    //$new_profile->set('field_lehigh_id',$lehigh_user);
    $new_profile->set('field_email',$lehigh_user."@lehigh.edu");

    $new_profile->setOwnerId($author_id);  //set the author to the new faculty member
    $new_profile->enforceIsNew();
    $new_profile->save();

    echo("Seems like everything worked from the add_new_profile function in AddCasProfile.php \n This echo should not appear in Drupal. Add error checking and better output with echos if the command line presents any persistent errors. \n");
}