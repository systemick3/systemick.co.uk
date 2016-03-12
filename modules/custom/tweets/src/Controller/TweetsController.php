<?php
/**
 * @file
 * Contains \Drupal\tweets\Controller\TweetsController.
 */

namespace Drupal\tweets\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TweetsController extends ControllerBase {

  protected $tweets;

  public function __construct($tweets) {
    $this->tweets = $tweets;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tweets.get_tweets')->getTweets()
    );
  }

  public function content() {
    $config = \Drupal::config('tweets.settings');

    $username = '<a href="http://www.twitter.com/' . $config->get('twitter_username')  . '">@' . $config->get('twitter_username')  . '</a>';

    return array(
      '#theme' => 'tweets_block',
      '#username' => $username,
      '#tweets' => $this->tweets,
      '#attached' => array(
        'library' =>  array(
          'tweets/base'
        ),
      ),
    );

    return array(
        '#type' => 'markup',
        '#markup' => $this->t('Hello, Systemick World!'),
    );
  }
}
?>
