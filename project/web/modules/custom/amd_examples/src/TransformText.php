<?php

namespace Drupal\amd_examples;

use Drupal\Core\Logger\LoggerChannelFactory;

/**
 * Provides functions to transform text.
 */
class TransformText {

    /**
     * Logger factory.
     * 
     * @var Drupal\Core\Logger\LoggerChannelFactory
     */
    protected $logger;

    public function __construct(LoggerChannelFactory $loggerFactory) {
        $this->logger = $loggerFactory->get('amd_examples');
    }

    public function reverse($text) {
        $this->logger->notice("Text reverted " . $text);
        return strrev($text);
    }

    public function uppercase($text) {
        $this->logger->notice("Text uppercased " . $text);
        return strtoupper($text);
    }

    public function titleCase($text) {
        $this->logger->notice("Text titlecased " . $text);
        return ucfirst($text);
    }
}