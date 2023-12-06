<?php

namespace Drupal\amd_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\amd_examples\TransformText;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "amd_examples_example",
 *   admin_label = @Translation("AMD Username"),
 *   category = @Translation("amd_examples")
 * )
 */
class AmdExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Current User.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * Text transformer.
   * 
   * @var Drupal\amd_examples\TransformText
   */
  protected $textTransformer;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxy $current_user, TransformText $text_transformer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->textTransformer = $text_transformer;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('text_transformer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = 'Hello ' . $this->currentUser->getAccountName();
    $new_text = $this->textTransformer->reverse($text);

    $build['content'] = [
      '#markup' => $new_text,
    ];
    return $build;
  }

}
