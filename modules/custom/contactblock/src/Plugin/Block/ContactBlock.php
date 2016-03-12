<?php
/**
 * Provides a Block to display the contact form
 *
 * @Block(
 *   id = "contact_block",
 *   admin_label = @Translation("Contact block"),
 * )
 */

namespace Drupal\contactblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

class ContactBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $default_form = \Drupal::config('contact.settings')->get('default_form');
    $entity = \Drupal::entityManager()->getStorage('contact_form')->load($default_form);

    $message = \Drupal::entityManager()
      ->getStorage('contact_message')
      ->create(array(
        'contact_form' => $entity->id(),
      ));

    $form = \Drupal::service('entity.form_builder')->getForm($message);

    $build = array();
    $build['strapline'] = array('#markup' => '<div class="hello">' . $this->t('Interested in working together or just want to say hello?') . '</div>');
    $build['email'] = array('#markup' => '<div class="email">hello@systemick.co.uk</div>');
    $build['phone'] = array('#markup' => '<div class="phone">07878 972159</div>');
    $build['clear'] = array('#markup' => '<div class="clearfix"></div>');
    $build['form'] = $form;

    return $build;
  }
}
?>
