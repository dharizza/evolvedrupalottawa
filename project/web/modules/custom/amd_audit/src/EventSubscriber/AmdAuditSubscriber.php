<?php

namespace Drupal\amd_audit\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\Component\EventDispatcher\Event;

/**
 * AMD Audit event subscriber.
 */
class AmdAuditSubscriber implements EventSubscriberInterface {

  /**
   * Event handler for createDeletionRecord
   *
   * @param Drupal\Component\EventDispatcher\Event $event
   *   Response event.
   */
  public function createDeletionRecord(Event $event) {
    $deleted = $event->getEntity();
    ksm($deleted);

    $record = \Drupal::entityTypeManager()
      ->getStorage('deletion_record')
      ->create([
        'title' => 'DELETED' . $deleted->label(),
        'field_title' => $deleted->label(),
        'field_bundle' => $deleted->bundle(),
        'field_entity_type' => $deleted->getEntityTypeId(),
        // 'field_entity_author' => $deleted->
      ]);
      $record->save();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      EntityHookEvents::ENTITY_PRE_DELETE => ['createDeletionRecord'],
    ];
  }

}
