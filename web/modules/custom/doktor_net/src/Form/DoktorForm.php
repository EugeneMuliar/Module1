<?php

namespace Drupal\doktor_net\Form;

use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Twig\Error\RuntimeError;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Database\Database;

/**
 * Implements an example form.
 */
class DoktorForm extends FormBase
{
  public function getFormId()
  {
    return 'doktor_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cat Name'),
      '#description' => $this->t('Enter cat name. Note that name must be longer than 2 characters and shorter than 32 characters'),
      '#maxlength' => 32,
      '#required' => TRUE,
      '#suffix' => '<span class="name-valid-message valid-message"></span>'
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#description' => $this->t('Email must looks like \'text@text.text \'  '),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'change',
      ],
      '#suffix' => '<span class="email-valid-message valid-message"></span>'
    ];
    $form['cats_image'] = [
      '#type' => 'managed_file',
      '#name' => 'cats_img',
      '#title'=>$this->t('Your cats image:'),
      '#upload_location' => 'public://img',
      '#description' => $this->t('The file must be .jpeg, .jpg or .png format and less than 2MB.'),
//      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2*1024*1024],
        ],
      '#ajax' => [
        'callback' => '::validateIMGAjax',
        'event' => 'load',
        '#field_suffix' => '<span class="img-valid-message valid-message"></span>',
      ],

    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('ADD CAT'),
      '#ajax' => [
        'callback' => '::submitAjaxMessage',
        'event' => 'click',
      ],

    ];

    return $form;
  }
  //Function that validate Name field on its length
  public function validateName(array &$form, FormStateInterface $form_state)
  {
    $cats_name_len = strlen($form_state->getValue('cats_name'));
    if ($cats_name_len <= 2) {
      return FALSE;
    }
    return TRUE;
  }
  //Function that validate Name and Image field with Ajax
  public function submitAjaxMessage(array &$form, FormStateInterface $form_state)
  {
    $validName = $this->validateName($form, $form_state);
    $validImage = $this->IsImageSet($form, $form_state);
    $response = new AjaxResponse();

    if ($validName){
      $name_css = ['border' => '1px solid green'];
      $name_message = $this->t('Name is cool.');
    }
    else {
      $name_css = ['border' => '1px solid red'];
      $name_message = $this->t('Name is too short.');
    }
    $response->addCommand(new CssCommand('#edit-cats-name', $name_css));
    $response->addCommand(new HtmlCommand('.name-valid-message', $name_message));

    if (!$validImage){
      $response->addCommand(new AlertCommand('Please set cat image'));
    }



    return $response;
  }
  //Function that validate Email field
  public function validateEmail(array &$form, FormStateInterface $form_state)
  {
    if (filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      return TRUE;
    }
    return FALSE;
  }
  //Function that validate Email field with Ajax
  public function validateEmailAjax(array &$form, FormStateInterface $form_state)
  {
    $valid = $this->validateEmail($form, $form_state);
    $response = new AjaxResponse();
    if ($valid) {
      $css = ['border' => '1px solid green'];
      $message = $this->t('Email is ok.');
    } else {
      $css = ['border' => '1px solid red'];
      $message = $this->t('Email is not valid.');
    }
    $response->addCommand(new CssCommand('#edit-email', $css));
    $response->addCommand(new HtmlCommand('.email-valid-message', $message));
    return $response;
  }
  public function IsImageSet(array &$form, FormStateInterface $form_state)
  {
    $picture = $form_state->getValue('cats_image');

    if(!empty($picture[0])){
      return TRUE;
    }
    return FALSE;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {

  }
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $picture = $form_state->getValue('cats_image');
    $current_date = date('d/m/y h:i:s',  strtotime('+3 hour'));

    //check fields are valid
    if($this->validateName($form, $form_state) && $this->validateEmail($form, $form_state) && $this->IsImageSet($form, $form_state)){

        //save file as permanent
        $file = File::load($picture[0]);
        $file->setPermanent();
        $file->save();

        $picture_name =  $file->getFileUri();
        $cat = [
          'cats_name' => $form_state->getValue('cats_name'),
          'email' => $form_state->getValue('email'),
          'fid' => $picture[0],
          'timestamp' => $current_date,
        ];

        \Drupal::database()->insert('cats')->fields($cat)->execute();
      header('Location::/doktor_net/cats');
    }
  }


}
