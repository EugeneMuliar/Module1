<?php

namespace Drupal\doktor_net\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

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


    // Get data from database
    $query = \Drupal::database()->select('cats', 'c');
    $query->fields('c', ['id', 'cats_name', 'email', 'fid', 'timestamp'])->orderBy('timestamp', 'desc');
    $data = $query->execute()->fetchAll();
    $cats = [];

    foreach ($data as $fields){
      $file = File::load($fields->fid);
      $uri = $file->getFileUri();
      $cat_image = [
        '#theme' => 'image_style',
        '#style_name' => 'wide',
        '#uri' => $uri,
        '#title' => 'Your Cat',
        '#width' => 170,
        '#height' => 170,
      ];
      $cats[] = [
        'id' => $fields->id,
        'cats_name' => $fields->cats_name,
        'email' => $fields->email,
        'fid' => $cat_image,
        'created_date' => $fields->timestamp,
      ];
    }
    $build = [
      '#theme' => 'my-template',
      '#cats' => $cats,
      '#form' => $doktorform,

    ];
    return  $build;
  }

}
