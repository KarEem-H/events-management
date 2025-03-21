<?php

namespace Drupal\events_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Messenger;

class EventsController extends ControllerBase
{
    // public function listEvents()
    // {

    //     $header_table = [
    //         'id' => ['data' => $this->t('ID')],
    //         'title' => ['data' => $this->t('Title')],
    //         'image' => ['data' => $this->t('Image')],
    //         'description' => ['data' => $this->t('Description')],
    //         'start_time' => ['data' => $this->t('Start Time')],
    //         'end_time' => ['data' => $this->t('End Time')],
    //         'category' => ['data' => $this->t('Category')],
    //         'operations' => ['data' => $this->t('Operations')],
    //     ];
    //     $row = [];
    //     $query = Database::getConnection()->select('events', 'm')
    //         ->fields('m', ['id', 'title', 'image', 'description', 'start_time', 'end_time', 'category']);
    //     $results = $query->execute()->fetchAll();

    //     foreach ($results as $event) {
    //         $deleteUrl = Url::fromUserInput('/events/delete/' . $event->id);
    //         $editUrl = Url::fromUserInput('/events/data?id=' . $event->id);
    //         $row[] = [
    //             'id' => $event->id,
    //             'title' => $event->title,
    //             'image' => $event->image,
    //             'description' => $event->description,
    //             'start_time' => date('Y-m-d H:i:s', $event->start_time),
    //             'end_time' => date('Y-m-d H:i:s', $event->end_time),
    //             'category' => $event->category,
    //             'operations' => Link::fromTextAndUrl('Edit',  $editUrl)->toString(),
    //             'operations1' => Link::fromTextAndUrl('Delete', $deleteUrl)->toString(),
    //         ];
    //     }

    //     $add = Url::fromUserInput('/events/data');

    //     $text = 'Add Event';
    //     $data['table'] = [
    //         '#type' => 'table',
    //         '#header' => $header_table,
    //         '#rows' => $row,
    //         '#empty' => $this->t('No events found'),
    //         '#caption' => Link::fromTextAndUrl($text, $add)->toString(),
    //     ];

    //     $this->messenger()->addMessage('Record Listed Successfully');

    //     return  $data;
    // }

    public function listEvents() {
        $header_table = [
          'id' => ['data' => $this->t('ID')],
          'title' => ['data' => $this->t('Title')],
          'image' => ['data' => $this->t('Image')],
          'description' => ['data' => $this->t('Description')],
          'start_time' => ['data' => $this->t('Start Time')],
          'end_time' => ['data' => $this->t('End Time')],
          'category' => ['data' => $this->t('Category')],
          'operations' => ['data' => $this->t('Operations')],
        ];
    
        $rows = [];
        $query = Database::getConnection()->select('events', 'e')
          ->fields('e', ['id', 'title', 'image', 'description', 'start_time', 'end_time', 'category']);
        $results = $query->execute()->fetchAll();
    
        foreach ($results as $event) {
          $deleteUrl = Url::fromUserInput('/events/delete/' . $event->id);
          $editUrl = Url::fromUserInput('/events/data?id=' . $event->id);
          $rows[] = [
            'id' => $event->id,
            'title' => $event->title,
            'image' => $event->image,
            'description' => $event->description,
            'start_time' => date('Y-m-d H:i:s', $event->start_time),
            'end_time' => date('Y-m-d H:i:s', $event->end_time),
            'category' => $event->category,
            'operations' => [
              'data' => [
                '#type' => 'operations',
                '#links' => [
                  'edit' => [
                    'title' => $this->t('Edit'),
                    'url' => $editUrl,
                  ],
                  'delete' => [
                    'title' => $this->t('Delete'),
                    'url' => $deleteUrl,
                  ],
                ],
              ],
            ],
          ];
        }
    
        $addUrl = Url::fromUserInput('/events/data');
        $addLink = Link::fromTextAndUrl($this->t('Add Event'), $addUrl)->toString();
    
        $build['table'] = [
          '#type' => 'table',
          '#header' => $header_table,
          '#rows' => $rows,
          '#empty' => $this->t('No events found'),
          '#caption' => $addLink,
        ];
    
        $this->messenger()->addMessage($this->t('Record Listed Successfully'));
    
        return $build;
      }
}
