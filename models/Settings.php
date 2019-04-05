<?php namespace Sensory5\Seo\Models;

use Model;

class Settings extends Model {

    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'sensory5_seo_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
    protected $cache = [];

    public $attachOne = [
        'og_image' => ['System\Models\File']
    ];
}