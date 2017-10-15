<?php

/**
 * @file
 * Contains \Drupal\system\Form\ImageToolkitForm.
 */

namespace Drupal\tweets\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Enable user to configure which Twitter feeds can be included as a block.
 */
class TweetsFeedsForm extends ConfigFormBase {



  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['tweets.feeds'];
  }

  /**
   * Form with 'add more' and 'remove' buttons.
   *
   * This example shows a button to "add more" - add another textfield, and
   * the corresponding "remove" button.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('tweets.feeds');

    $form['description'] = [
      '#markup' => '<div>' . t('Add twitter feeds.') . '</div>',
    ];

    // Gather the number of names in the form already.
    $num_feeds = $form_state->get('num_feeds');
    if ($num_feeds === NULL && $values = $config->get('feeds')) {
      //$values = $config->get('feeds');
      $num_feeds = count($values);
      if ($num_feeds > 0) {
        $form_state->set('num_feeds', $num_feeds);
      }
    }
    else {
      $values = $form_state->getValue(['feeds_fieldset', 'feed']);
    }
    // We have to ensure that there is at least one name field.
    if ($num_feeds === NULL) {
      $feeds_field = $form_state->set('num_feeds', 1);
      $num_feeds = 1;
    }

    $form['#tree'] = TRUE;
    $form['feeds_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('adding a field will cerate a block containing tweets from that feed'),
      '#prefix' => '<div id="feeds-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $num_feeds; $i++) {
      $form['feeds_fieldset']['feed'][$i] = [
        '#type' => 'textfield',
        '#title' => t('Feed'),
        '#default_value' => isset($values[$i]) ? $values[$i] : '',
      ];
    }

    $form['feeds_fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    $form['feeds_fieldset']['actions']['add_feed'] = [
      '#type' => 'submit',
      '#value' => t('Add a feed'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'feeds-fieldset-wrapper',
      ],
    ];
    // If there is more than one name, add the remove button.
    if ($num_feeds > 1) {
      $form['feeds_fieldset']['actions']['remove_feed'] = [
        '#type' => 'submit',
        '#value' => t('Remove feed'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'feeds-fieldset-wrapper',
        ],
      ];
    }
    $form_state->setCached(FALSE);
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tweets.feeds';
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    $feeds_field = $form_state->get('num_feeds');
    return $form['feeds_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $feeds_field = $form_state->get('num_feeds');
    $add_button = $feeds_field + 1;
    $form_state->set('num_feeds', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $feeds_field = $form_state->get('num_feeds');
    if ($feeds_field > 1) {
      $remove_button = $feeds_field - 1;
      $form_state->set('num_feeds', $remove_button);
    }
    $form_state->setRebuild();
  }

  /**
   * Final submit handler.
   *
   * Reports what values were finally set.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('tweets.feeds');
    
    $values = $form_state->getValue(['feeds_fieldset', 'feed']);
    $config->set('feeds', $values);
    $config->save();

    $output = t('These feeds are being added: @feeds', [
      '@feeds' => implode(', ', $values),
    ]
    );
    drupal_set_message($output);
  }

}
