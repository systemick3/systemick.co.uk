<?php

/**
 * @file
 * Contains \Drupal\tweets\Plugin\Derivative\TweetsBlock.
 */

namespace Drupal\tweets\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides block plugin definitions for tweets blocks.
 *
 * @see \Drupal\tweets\Plugin\Block\TweetsBlock
 */
class TweetsBlock extends DeriverBase {
  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $config = \Drupal::config('tweets.feeds');
    foreach ($config->get('feeds') as $item) {
      $this->derivatives[$item] = $base_plugin_definition;
      $this->derivatives[$item]['admin_label'] = 'Tweets Block - ' . $item;
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }
}
