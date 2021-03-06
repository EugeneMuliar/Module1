<?php

namespace Drupal\doktor_net\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DeleteForm extends ConfirmFormBase{

  public  $catID;

  public function getFormId()
  {
    return 'Delete Cat';
  }
  public function buildForm(array $form, FormStateInterface $form_state, $catID = NULL){
    $this->id = $catID;
    return parent::buildForm($form, $form_state);
  }
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $query = \Drupal::database();
    $query->delete('cats')
      ->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('You deleted your cat');
    $form_state->setRedirect('doktor_net.content');
  }

  public function getQuestion()
  {
   return $this->t('Do you want to delete this Cat?');
  }

  public function getCancelUrl()
  {
    return new Url('doktor_net.content');
  }
  public function getDescription() {
    return $this->t('Do you want to delete ?');
  }
  public function getConfirmText() {
    return $this->t('Delete');
  }
  public function getCancelText() {
    return t('Cancel');
  }
}
