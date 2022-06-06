<?php

namespace Drupal\cas_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CreateProfileForm extends FormBase {

    public function getFormId() {
        return 'cas_profile_create_profile_form';
      }

    public function buildForm(array $form, FormStateInterface $form_state){
       
        $form['username'] = array(
            '#type' => 'textfield',
            '#title' => 'NEW Profile & User',
            '#description' => 'Enter the Lehigh Username of the person you wish to add to this site, creates a user and a new CAS Profile.',
            '#required' => FALSE,
            '#weight' => 1,
        );

        $form['updatename'] = array(
            '#type' => 'textfield',
            '#title' => 'Profile to Update',
            '#description' => 'Enter the Lehigh Username of the person for which you wish to refresh the data, it will compare what is in the profile again banner info',
            '#required' => FALSE,
            '#weight' => 3,
        );

        $form['actions'] = [
            '#type' => 'actions',
          ];
        
        $form['submitCreate'] = [
            '#type' => 'submit',
            '#value' => 'Add User and Profile',
            '#submit' => array([$this, 'submitCreate']),
            "#weight" => 2,
        ];
        
        //$form['actions']['submitUpdate'] = [
        $form['submitUpdate'] = [
            '#type' => 'submit',
            '#value' => 'Update Single Profile',
            "#weight" => 4,
            '#submit' => array([$this, 'submitUpdate']),
            
        ];

        $markup = '<hr>';
        $form['markupseperator'] = [
            '#type' => 'markup',
            '#markup' => $markup,
            '#weight' => 5,
        ];
        
        $form['submitCompareAll'] = [
            '#type' => 'submit',
            '#value' => 'Compare ALL profiles to banner and update',
            "#weight" => 6,
            '#submit' => array([$this, 'submitCompareAll']),
            '#description' => 'Use with Caution, it will attempt to update all Faculty/Profiles based on the information in banner.'
        ];

        // I don't need the normal submit button, because each portion of this page needs it's own submit button

        return $form;
    }

    public function submitCreate(array &$form, FormStateInterface $form_state) {
        $new_profile = $form_state->getValue('username');

        require __DIR__.'/../addCasProfile.php';
        add_new_profile($new_profile);

        //probably try to add some criteria here to confirm it did indeed work
        $message = \Drupal::messenger();
        $message->addMessage('It worked! Added user and profile:  '.$new_profile);
    }

    public function submitUpdate(array &$form, FormStateInterface $form_state) {
        // send info to a different function in compareProfileInfo.php
        
        $update_profile = $form_state->getValue('updatename');

        $message = \Drupal::messenger();
        $message->addMessage('You have submitted and update for profile: '.$update_profile.' If there was any update to perform there will be another message below.');

        require __DIR__.'/../compareProfileInfo.php';
        singleProfileUpdate($update_profile);
    }

    public function submitCompareAll(array &$form, FormStateInterface $form_state) {
        // send info to a different function in compareProfileInfo.php
        $message = \Drupal::messenger();
        $message->addMessage('You have updated ALL profiles');
        
        require __DIR__.'/../compareProfileInfo.php';
        updateAllProfiles();
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {        
        $message = \Drupal::messenger();
        $message->addMessage('I think this function is required.  so here it is.');
    }

}
