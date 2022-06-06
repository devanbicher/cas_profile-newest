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

        // $path = \Drupal::service('path_alias.manager')->getPathByAlias('/faculty-staff/' . $profile);
        // if(preg_match('/node\/(\d+)/', $path, $matches)) {
        //     $node = \Drupal\node\Entity\Node::load($matches[1]);
        // }
        // $view_builder = \Drupal::entityTypeManager()->getViewBuilder($node->getEntityTypeId());

        // $node_view = $view_builder->view($node);

        // $build = [];

        // $build = array(
        //     '#theme' => 'cas_profile',
        //     '#content' => $node_view,
        // );

        // $build['#attached']['library'][] = 'cas_profile/tabselect';
        // $build['#attached']['drupalSettings']['tabselect']['pagename'] = $pagename;


        $response = new RedirectResponse('/faculty-staff/' . $profile . '#' . $tab);
        return $response;
      
        //return $build;
    }

}
