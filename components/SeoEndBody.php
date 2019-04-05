<?php namespace Sensory5\Seo\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Sensory5\Seo\models\Settings;

class SeoEndBody extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'sensory5.seo::lang.component.meta.name',
            'description' => 'sensory5.seo::lang.component.meta.description',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun() {
        $settings = Settings::instance();
        $this->page['endBodyTags'] =$settings->endbody_tags;
    }
}
