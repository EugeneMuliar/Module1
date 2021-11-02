<?php

namespace Drupal\doktor_net\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Twig\Error\RuntimeError;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Implements an example form.
 */
class DoktorForm extends FormBase {
  public function getFormId() {
    return 'doktor_form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cat Name'),
      '#description' => $this->t('Enter cat name. Note that name must be longer than 2 characters and shorter than 32 characters'),
      '#maxlength' => 32,
      '#required'=> TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('ADD CAT'),
      '#ajax' => [
        'callback' => '::setAjaxMessage',
      ]
    ];

    return $form;
  }
  public function setAjaxMessage(array &$form, FormStateInterface $form_state){
    $response = new AjaxResponse();
    if(strlen($form_state->getValue('cats_name')) < 2){
      $response->addCommand($this->validateForm());
    }
    return $response;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if(strlen($form_state->getValue('cats_name')) < 2){
      $form_state->setErrorByName('cat_name', $this->t('Name is too short.'));
    }
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    foreach ($form_state->getValues() as $key => $value) {
//      \Drupal::messenger()->addStatus($key . ': ' . $value);
//    }
  }
}
