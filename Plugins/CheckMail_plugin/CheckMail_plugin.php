<?php

namespace JarvisPHP\Plugins\CheckMail_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * CheckMail plugin - check an imap mailbox for unread emails
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class CheckMail_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 5;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        $answer = '';
        
        //Load credentials from json config
        if(file_exists('Plugins/CheckMail_plugin/credentials.json')) {
            $json_config = json_decode(file_get_contents('Plugins/CheckMail_plugin/credentials.json'));
        }

        if($json_config) {

            $mbox = imap_open($json_config->connection_string, $json_config->user, $json_config->password, OP_READONLY);

            $uids   = imap_search($mbox, 'UNSEEN', SE_UID);
            
            if(is_array($uids)) {
                $answer = sprintf(JarvisLanguage::translate('you_have_new_messages',get_called_class()), count($uids));
            } else {
                $answer = JarvisLanguage::translate('no_messages',get_called_class());
            }

            if(preg_match(JarvisLanguage::translate('preg_match_read',get_called_class()), $command) && is_array($uids)) {
                //Read last message
                $result = imap_fetch_overview($mbox, end($uids), FT_UID);
                $mail = end($result);
                $answer = JarvisLanguage::translate('from',get_called_class()).': '.$mail->from.'. '.JarvisLanguage::translate('subject',get_called_class()).': '.imap_utf8($mail->subject);
                JarvisSession::terminate();
            }
            imap_close($mbox);
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
        return true;
    }
}
