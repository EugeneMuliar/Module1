<?php

namespace Drupal\doktor_net\Form;

use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Twig\Error\RuntimeError;
use Drupal\Core\Ajax\AjaxResponse;

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
      '#description' => $this->t('The file must be .jpeg, .jpg or .png format and less than 2MB.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2*1024*1024],
        ],
      '#ajax' => [
        'callback' => '::validateIMGAjax',
        'event' => 'load',
      ],
      '#suffix' => '<span class="img-valid-message valid-message"></span>'
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('ADD CAT'),
      '#ajax' => [
        'callback' => '::submitAjaxMessage',
        'event' => 'click',
      ]
    ];

    return $form;
  }



  public function validateName(array &$form, FormStateInterface $form_state)
  {
    $cats_name_len = strlen($form_state->getValue('cats_name'));
    if ($cats_name_len <= 2) {
      return FALSE;
    }
    return TRUE;
  }
  public function submitAjaxMessage(array &$form, FormStateInterface $form_state)
  {
    $validName = $this->validateName($form, $form_state);
    $response = new AjaxResponse();
    if ($validName){
      $css = ['border' => '1px solid green'];
      $message = $this->t('Name is cool.');
    }
    else {
      $css = ['border' => '1px solid red'];
      $message = $this->t('Name is too short.');
    }
    $response->addCommand(new CssCommand('#edit-cats-name', $css));
    $response->addCommand(new HtmlCommand('.name-valid-message', $message));

    return $response;
  }
  public function validateEmail(array &$form, FormStateInterface $form_state)
  {
    if (filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      return TRUE;
    }
    return FALSE;
  }
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

//  public function validateIMG(array &$form, FormStateInterface $form_state){
//    $file = file_save_upload('cats_image', [
//      'file_validate_is_image'=>['png jpg jpeg'],
//      'file_validate_size'=> [2*1024*1024],
//      ]);
//    if($file){
//      return TRUE;
//    }
//    else{
//      return FALSE;
//    }
//  }
//  public function validateIMGAjax(array &$form, FormStateInterface $form_state){
//    $valid = $this->validateIMG($form, $form_state);
//    $response = new AjaxResponse();
//    if ($valid) {
//      $css = ['border' => '1px solid green'];
//      $message = $this->t('Image is ok.');
//    } else {
//      $css = ['border' => '1px solid red'];
//      $message = $this->t('Image is not valid.');
//    }
//    $response->addCommand(new CssCommand('#edit-cats_image', $css));
//    $response->addCommand(new HtmlCommand('.img-valid-message', $message));
//    return $response;
//  }
  public function validateForm(array &$form, FormStateInterface $form_state)
  {

  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }


}
