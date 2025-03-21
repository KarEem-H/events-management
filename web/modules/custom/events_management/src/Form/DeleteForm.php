<?php

namespace Drupal\events_management\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Drupal\Core\Messenger;

/**
 * Provides a form for deleting an event.
 */
class DeleteForm extends ConfirmFormBase
{

    /**
     * The ID of the event to delete.
     *
     * @var int
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'events_management_delete_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        return $this->t('Are you sure you want to delete event %id?', ['%id' => $this->id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl()
    {
        return new Url('events_management.events_controller_list');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->t('Are you sure you want to delete event %id?', ['%id' => $this->id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return $this->t('Delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return $this->t('Cancel');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
    {
        $this->id = $id;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $connection = Database::getConnection();
        $connection->delete('events')
            ->condition('id', $this->id)
            ->execute();

        $this->messenger()->addMessage($this->t('Event %id has been deleted.', ['%id' => $this->id]));

        $form_state->setRedirect('events_management.events_controller_list');
    }

    //   public function validateForm(array &$form, FormStateInterface $form_state) {
    //     $query = Database::getConnection();
    //     $query->delete('events')
    //       ->condition('id', $this->id)
    //       ->execute();

    //       $this->messenger()->addMessage($this->t('Event %id has been deleted.', ['%id' => $this->id]));

    //         $form_state->setRedirect('events_management.events_controller_list');
    //   }

}
