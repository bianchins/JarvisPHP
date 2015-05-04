<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JarvisPHP {
    
    static $rules = array();
    
    static $tokens = array();
    
    static $active_plugins = array();
    
    static function initialize() {
        //Autoloading classes
        spl_autoload_register(function($className)
        {
            $namespace=str_replace("\\","/",__NAMESPACE__);
            $className=str_replace("\\","/",$className);
            $class="plugins/".(empty($namespace) ? "" : $namespace."/")."{$className}.php";
            include_once($class);
        });

        //Session
        session_start();
    }
    
    static function enablePlugin($plugin) {
        $pluginToEnable = new $plugin;
        //Load rules for the plugin
        $pluginRules = $pluginToEnable->loadRules();
        //Insert in global list of rules
        foreach($pluginRules as $rule) {
            array_push(JarvisPHP::$rules, array($plugin, $rule));
        }
        $pluginsTokens = $pluginToEnable->loadTokens();
        foreach($pluginsTokens as $token) {
            array_push(JarvisPHP::$tokens, array($plugin, $token));
        }
        
        array_push(JarvisPHP::$active_plugins, $plugin);
        
        //Clear variables
        unset($pluginRules);
        unset($pluginToEnable);
    }
    
    /**
     * Parse the command and execute the plugin
     * @param string $command
     */
    static function elaborateCommand($command) {
        //Verify if there is an active plugin
        if(!empty($_SESSION['active_plugin'])) {
            $plugin_class = $_SESSION['active_plugin'];
            $plugin = new $plugin_class();
            $plugin->answer($command);
        }
        else {
            //Token parsing (first pass)
            
        
            
            //TODO ntltools parsing
            $trainingSet = new NlpTools\Documents\TrainingSet(); // will hold the training documents
            $tokenizer = new NlpTools\Tokenizers\WhitespaceTokenizer(); // will split into tokens
            $ff = new NlpTools\FeatureFactories\DataAsFeatures(); // see features in documentation
            
            // ---------- Training ----------------
            foreach (JarvisPHP::$rules as $d)
            {
                    $trainingSet->addDocument(
                            $d[0], // class
                            new NlpTools\Documents\TokensDocument(
                                    $tokenizer->tokenize($d[1]) // The actual document
                            )
                    );
            }
            $model = new NlpTools\Models\FeatureBasedNB(); // train a Naive Bayes model
            $model->train($ff,$trainingSet);

            $cls = new NlpTools\Classifiers\MultinomialNBClassifier($ff,$model);

            $document = new NlpTools\Documents\TokensDocument(
                        $tokenizer->tokenize($command) // The document
                    );
            
            $prediction = $cls->classify(
            JarvisPHP::$active_plugins, // all possible classes
                    $document
            );

             echo $command. " " . $prediction;
             echo $cls->getScore($prediction, $document).PHP_EOL;
        }
    }

    
} //JarvisPHP