<?php

namespace Flights\App\Settings;

class Settings
{
    public function getSettings(): array
    {
        return [
            'settings' => [
        
                'templates.path' => __DIR__ . '/../templates/',
                'addContentLengthHeader' => false, // Allow the web server to send the content-length header
                'root' => dirname(__DIR__),
                'temp' => dirname(__DIR__) . '/tmp',
                'public' => dirname(__DIR__) . '/public',
                'displayErrorDetails' => true,
                // Renderer settings
                'twig' => [
                    'path' => dirname(__DIR__) . '/templates'
                ],
        
                // Monolog settings
                'logger' => [
                    'name' => 'flights-slim-app',
                    'file' => dirname(__DIR__) . '/tmp' . '/logs/app.log',
                    'level' => \Monolog\Logger::ERROR,
                ],
            ]
        ];
    }

}