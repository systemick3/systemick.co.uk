<?php

/**
 * @file
 * Contains Drupal\tweets\TweetsClient.
 *
 */

namespace Drupal\tweets;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Url;

/**
 * Class TweetsClient.
 *
 * @package Drupal\tweets
 */
class TweetsClient {

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $config_factory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->config_factory = $config_factory;
  }

  public function getTweets($search_term) {
    // Get tweets
    $tweets = $this->getBearerToken()->performRequest($search_term);

    // Grab text and created at
    $formatted_tweets = array();
    foreach($tweets as $key => $tweet) {
      $formatted_tweets[$key]['text'] = $this->prepareTweet($tweet['text']);
      $formatted_tweets[$key]['created_at'] = $tweet['created_at'];
    }

    return $formatted_tweets;
  }

  private function getBearerToken() {
    $config = \Drupal::config('tweets.settings');

    $encoded_key = base64_encode($config->get('twitter_api_key') . ':' . $config->get('twitter_secret_key'));
    $data = 'grant_type=client_credentials';

    $header = array(
      'Authorization: Basic ' . $encoded_key,
      'Content-Length: ' . strlen($data),
    );

    $options = array(
      CURLOPT_HTTPHEADER => $header,
      CURLOPT_POST => true,
      CURLOPT_ENCODING => 'gzip',
      CURLOPT_HEADER => false,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_AUTOREFERER => true,
      CURLOPT_USERAGENT => 'My Twitter App v1.0.23',
      CURLOPT_FOLLOWLOCATION => true,
    );

    $feed = curl_init('https://api.twitter.com/oauth2/token');
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    $parsed_response = json_decode($json, true);
    $this->access_token = $parsed_response['access_token'];

    return $this;
  }

  private function performRequest($search_term) {
    $config = \Drupal::config('tweets.settings');

    $header = array(
      'Authorization: Bearer ' . $this->access_token,
    );

    $options = array(
      CURLOPT_HTTPHEADER => $header,
      CURLOPT_ENCODING => 'gzip',
      CURLOPT_HEADER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_AUTOREFERER => true,
      CURLOPT_USERAGENT => 'Systemick\'s Twitter App v1.0.0',
      CURLOPT_FOLLOWLOCATION => true,
    );


    //$feed = curl_init('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $config->get('twitter_username') . '&count=' . $config->get('max_tweets'));
    $feed = curl_init('https://api.twitter.com/1.1/search/tweets.json?q=' . $search_term . '&count=' . $config->get('max_tweets'));
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);
    $parsed_response = json_decode($json, true);

    if (isset($parsed_response['statuses'])) {
      return $parsed_response['statuses'];
    }
    else {
      return $parsed_response;
    }
  }

  /**
   * Adds #hashtags, @mentions, and links to a tweet body
   * @param array $tweet
   * @return array
   */
  private function prepareTweet($tweet = array()) {
    $tweet = $this->formatLinks($tweet);
    $tweet = $this->formatHashtags($tweet);
    $tweet = $this->formatMentions($tweet);

    return $tweet;
  }

  private function formatHashtags($tweet = array()) {
    if(strpos($tweet,'#') !== false) {
      $tweet = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', ' <a href="https://twitter.com/hashtag/$2" target="_blank">#$2</a>', $tweet);
    }
    return $tweet;
  }

  private function formatLinks($tweet = array()) {
    if((strpos($tweet,'http') !== false)) {
      $pattern = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";
      $tweet = preg_replace($pattern, '<a href="$1" target="_blank">$1</a>', $tweet);
    }
    return $tweet;
  }

  private function formatMentions($tweet = array()) {
    if(strpos($tweet,'@') !== false) {
      $tweet = preg_replace('/(^|\s)@(\w*[a-zA-Z_]+\w*)/', ' <a href="https://twitter.com/$2" target="_blank">@$2</a>', $tweet);
    }
    return $tweet;
  }

}
