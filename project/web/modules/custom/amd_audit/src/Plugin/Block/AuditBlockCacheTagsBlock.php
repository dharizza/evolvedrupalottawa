<?php

namespace Drupal\amd_audit\Plugin\Block;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an audit block - cache tags block.
 *
 * @Block(
 *   id = "audit_block_cache_tags",
 *   admin_label = @Translation("Audit block - cache tags"),
 *   category = @Translation("Custom")
 * )
 */
class AuditBlockCacheTagsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new AuditBlockCacheTagsBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $storage = $this->entityTypeManager->getStorage('deletion_record');
    $results = $storage->getQuery()
      ->accessCheck(TRUE)
      ->sort('field_deleted', 'DESC')
      ->range(0, 3)
      ->execute();

    $output = '<h3>Recently deleted entities</h3><ul>';
    $records = $storage->loadMultiple($results);
    foreach ($records as $item) {
      $output = $output . '<li>' . $item->label->value . '</li>';
    }
    $output = $output . '</ul>';

    $build['content'] = [
      '#type' => 'markup',
      '#markup' => $output,
    ];
    return $build;
  }

  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['deletion_record_list']);
  }

}
