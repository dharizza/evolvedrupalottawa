<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World routes.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($name = NULL) {

    if ($name) {
      $output = "Hello " . $name;
    }
    else {
      $output = "Hello World!";
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output
    ];

    return $build;
  }

}
