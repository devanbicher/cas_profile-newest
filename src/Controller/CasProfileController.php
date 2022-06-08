<?php

namespace Drupal\cas_profile\Controller;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CasProfileController extends ControllerBase
{

    public function page_by_field($profile, $tab)
    {
        //We shouldn't do it this way, this is creating a redirect back to the same page everytime you click on a tab I think
        $response = new RedirectResponse('/faculty-staff/' . $profile . '#' . $tab);
        return $response;
    }

}
