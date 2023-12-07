<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Returns responses for Hello World routes.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($name = NULL) {

    if ($name) {
      $output = $this->t("Hello @name!", ['@name' => $name]);
    }
    else {
      $output = $this->t("Hello World!");
    }

    // $query = \Drupal::database()
    //   ->select('node', 'n')
    //   ->fields('n', ['nid', 'type'])
    //   ->condition('n.type', 'page', '=')
    //   ->execute()
    //   ->fetchAll();
    // ksm($query);

    $ids = \Drupal::entityQuery('node')
      ->condition('type', 'page')
      ->accessCheck(TRUE)
      ->execute();
    // $nodes = Node::loadMultiple($ids);

    $nodeManager = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $nodeManager->loadMultiple($ids);

    foreach ($nodes as $node) {
      ksm($node->getTitle());
    }

    // ksm($nodes);

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

  public function getNode($id) {
    $node = Node::load($id);

    // Link to node.
    // $link = $node->toLink()->toString();

    // Manually craft URL to node.
    $url = Url::fromRoute('entity.node.canonical', ['node' => $id]);
    ksm($url);
    $internal_link = Link::fromTextAndUrl($node->getTitle(), $url);
    ksm($internal_link);

    $build['content'] = [
      '#type' => 'item',
      '#markup' =>  $internal_link->toString(),
    ];

    return $build;
  }

}
