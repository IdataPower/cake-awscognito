<?php
use Cake\Core\Configure;

Configure::load('PowerSystem/CognitoSDK.awscognito');
collection((array)Configure::read('CognitoSDK.config'))->each(function ($file) {
    Configure::load($file);
});
