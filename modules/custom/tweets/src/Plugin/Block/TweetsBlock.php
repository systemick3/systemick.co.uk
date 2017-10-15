<?php

/**
 * @file
 * Contains \Drupal\system\Plugin\Block\SystemMenuBlock.
 */

namespace Drupal\tweets\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\tweets\TweetsClient;
use Drupal\Core\Url;

/**
 * Provides a block to display tweets.
 *
 * @Block(
 *   id = "tweets_block",
 *   admin_label = @Translation("Tweets"),
 *   category = @Translation("Media")
 * )
 */
class TweetsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\tweets\TweetsClient definition.
   */
  protected $tweets;

  /**
   * Creates a TweetsBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $tweets
   *   The formatted tweets from the client service for this block..
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $tweets) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->tweets = $tweets;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tweets.get_tweets')->getTweets()
    );
  }

  /**
  * {@inheritdoc}
  */
  public function build() {
    $build = [];
    $build['tweets_block']['#markup'] = $this->tweets;

    $config = \Drupal::config('tweets.settings');

    $username = '<a href="http://www.twitter.com/' . $config->get('twitter_username')  . '">@' . $config->get('twitter_username')  . '</a>';
    $tweets = array_slice($this->tweets, 0, 3);
    $more = \Drupal::l(t('More tweets'), Url::fromRoute('tweets.content'));

    return array(
      '#theme' => 'tweets_block',
      '#username' => $username,
      '#tweets' => $tweets,
      '#more' => $more,
      '#cache' => [
        'max-age' => 0,
      ],
      '#attached' => array(
        'library' =>  array(
          'tweets/base'
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = \Drupal::config('tweets.settings');

    return array(
      'username' => '',
      'max_tweets' => $config->get('max_tweets'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->configuration;

    $defaults = $this->defaultConfiguration();
    $options = range(0, $defaults['max_tweets']);

    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Twitter username'),
      '#default_value' => $config['username'],
      '#maxlength' => 512,
      '#description' => t('The Twitter username whose tweets will be displayed.'),
      '#required' => TRUE,
    );

    $form['num_tweets'] = array(
      '#type' => 'select',
      '#title' => $this->t('Number of tweets to display'),
      '#default_value' => $config['num_tweets'],
      '#options' => $options,
      '#description' => $this->t('This will be the number of tweets displayed in the block.'),
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['username'] = $form_state->getValue('username');
    $this->configuration['num_tweets'] = $form_state->getValue('num_tweets');
  }

}
?>
