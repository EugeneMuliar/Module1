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

    $element = [
      '#theme' => 'my-template',
      '#data' => 'Hello Cat',
    ];
    return $element;
  }

}
