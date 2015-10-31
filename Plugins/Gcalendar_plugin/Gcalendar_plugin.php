<?php

namespace JarvisPHP\Plugins\Gcalendar_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

define('APPLICATION_NAME', 'JarvisPHP Client');
define('CREDENTIALS_PATH', 'Plugins/Gcalendar_plugin/api-key.json');
define('CLIENT_SECRET_PATH', 'Plugins/Gcalendar_plugin/secret-client-key.json');
define('SCOPES', implode(' ', array(
  \Google_Service_Calendar::CALENDAR_READONLY)
));
define('_MAX_EVENTS', 4);

/**
 * Google Calendar plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Gcalendar_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 1;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        $answer = '';
        
        JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
        
        // Get the API client and construct the service object.
        $client = Gcalendar_plugin::getClient();
        
        if($client==null) return null;

        $service = new \Google_Service_Calendar($client);

        // Print the next _MAX_EVENTS events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
          'maxResults' => _MAX_EVENTS,
          'orderBy' => 'startTime',
          'singleEvents' => TRUE,
          'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        if (count($results->getItems()) == 0) {
          $answer = JarvisLanguage::translate('no_appointments',get_called_class());
        } else {
          
          foreach ($results->getItems() as $event) {
            $start = $event->start->dateTime;
            if (empty($start)) {
              $start = $event->start->date;
            }

            $date = new \DateTime($start);
            $answer.= sprintf(JarvisLanguage::translate('list_events',get_called_class()), $date->format('j'), JarvisLanguage::translate('month_'.$date->format('n'),get_called_class()), $date->format('H'), $date->format('i'), $event->getSummary())."\n";
          }
        }

        JarvisTTS::speak($answer);
        $response = new \JarvisPHP\Core\JarvisResponse($answer, JarvisPHP::getRealClassName(get_called_class()), true);
        $response->send();
    }
    /**
     * Get plugin's priority
     * @return int
     */
    function getPriority() {
        return $this->priority;
    }
    
    /**
     * Is it the right plugin for the command?
     * @param string $command
     * @return boolean
     */
    function isLikely($command) {
        return preg_match(JarvisLanguage::translate('preg_match_activate_plugin',get_called_class()), $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return false;
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient() {
      $client = new \Google_Client();
      $client->setApplicationName(APPLICATION_NAME);
      $client->setScopes(SCOPES);
      $client->setAuthConfigFile(CLIENT_SECRET_PATH);
      $client->setAccessType('offline');

      // Load previously authorized credentials from a file.
      $credentialsPath = CREDENTIALS_PATH;
      if (file_exists($credentialsPath)) {
        $accessToken = file_get_contents($credentialsPath);
      } else {
        return null;
      }
      $client->setAccessToken($accessToken);

      // Refresh the token if it's expired.
      if ($client->isAccessTokenExpired()) {
        $client->refreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, $client->getAccessToken());
      }
      return $client;
    }

}
