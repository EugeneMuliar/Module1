<?php

namespace Drupal\doktor_net\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController class.
 */
class DoktorController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {

    //Get form to the variable
    $doktorform['doktor_form'] = \Drupal::formBuilder()->getForm('Drupal\doktor_net\Form\DoktorForm');

    //Send values to template
    $build = [
      '#theme' => 'my-template',
      '#form' => $doktorform,
      '#data' => 'Hello Cat',
    ];
    return  $build;
  }

}
