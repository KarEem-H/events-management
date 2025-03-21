<?php

namespace Drupal\events_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a form for creating and updating events.
 */
class EventsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'events_management_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $id,
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
    ];

    $form['image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image'),
      '#required' => FALSE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
    ];

    $form['start_time'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Start Time'),
      '#required' => TRUE,
    ];

    $form['end_time'] = [
      '#type' => 'datetime',
      '#title' => $this->t('End Time'),
      '#required' => TRUE,
    ];

    $form['category'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Category'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    // If an ID is provided, load the existing event data.
    if ($id) {
      $connection = Database::getConnection();
      $query = $connection->select('events', 'e')
        ->fields('e', ['title', 'image', 'description', 'start_time', 'end_time', 'category'])
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();

      if ($query) {
        $form['title']['#default_value'] = $query['title'];
        $form['image']['#default_value'] = $query['image'];
        $form['description']['#default_value'] = $query['description'];
        $form['start_time']['#default_value'] = $query['start_time'];
        $form['end_time']['#default_value'] = $query['end_time'];
        $form['category']['#default_value'] = $query['category'];
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    if (preg_match('/[^a-zA-Z0-9]/', $title) == 0) {
        $form_state->setErrorByName('title', $this->t('Title must contain at least one letter or number.'));
    }
    $description = $form_state->getValue('description');
    if (strlen($description) < 10) {
        $form_state->setErrorByName('description', $this->t('Description must be at least 10 characters long.'));
    }
    $image = $form_state->getValue('image');
    if (!empty($image) && !filter_var($image, FILTER_VALIDATE_URL)) {
        $form_state->setErrorByName('image', $this->t('Image must be a valid URL.'));
    }
    $start_time = $form_state->getValue('start_time');
    if (strtotime($start_time) === FALSE) {
        $form_state->setErrorByName('start_time', $this->t('Start time must be a valid date and time.'));
    }
    $end_time = $form_state->getValue('end_time');
    if (strtotime($end_time) === FALSE) {
        $form_state->setErrorByName('end_time', $this->t('End time must be a valid date and time.'));
    }
    if (strtotime($start_time) >= strtotime($end_time)) {
        $form_state->setErrorByName('end_time', $this->t('End time must be after start time.'));
    }
    $category = $form_state->getValue('category');
    if (strlen($category) < 3) {
        $form_state->setErrorByName('category', $this->t('Category must be at least 3 characters long.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $id = $form_state->getValue('id');
    $fields = [
      'title' => $form_state->getValue('title'),
      'image' => $form_state->getValue('image'),
      'description' => $form_state->getValue('description'),
      'start_time' => strtotime($form_state->getValue('start_time')),
      'end_time' => strtotime($form_state->getValue('end_time')),
      'category' => $form_state->getValue('category'),
    ];

    if ($id) {
      // Update existing event.
      $connection->update('events')
        ->fields($fields)
        ->condition('id', $id)
        ->execute();
      $this->messenger()->addMessage($this->t('Event %id has been updated.', ['%id' => $id]));
    } else {
      // Insert new event.
      $connection->insert('events')
        ->fields($fields)
        ->execute();
      $this->messenger()->addMessage($this->t('New event has been created.'));
    }

    $form_state->setRedirect('events_management.events_controller_list');
  }

}