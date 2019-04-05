<?php namespace Sensory5\Seo\Classes;

use Sensory5\Seo\Models\Settings;
use Request;

class Helper {

    public $settings;

    public function __construct()
    {
        $this->settings = Settings::instance();
    }


    public function generateTitle($title)
    {
        $settings = $this->settings;

        if($settings->enable_title && !empty(trim($settings->title)))
        {
            $position = $settings->title_position;
            $site_title = $settings->title;

            if($position == 'prefix')
            {
                $new_title = $site_title . " | " . $title;
            }
            else
            {
                $new_title = $title . " | " . $site_title;
            }
        }
        else
        {
            $new_title = $title;
        }
        return $new_title;
    }

    function generateCanonicalUrl($url=NULL)
    {
        $settings = $this->settings;

        if($settings->enable_canonical_url)
        {
            if (is_null($url)) { $url = Request::url(); }
            return '<link rel="canonical" href="'. $url.'"/>';
        }

        return "";
    }

    public function generateOtherMetaTags()
    {
        $settings = $this->settings;

        if($settings->other_tags)
        {
            return $settings->other_tags;
        }

        return "";

    }

    public function generateEndBodyTags()
    {
        $settings = $this->settings;

        if($settings->endbody_tags)
        {
            return $settings->endbody_tags;
        }

        return "";

    }

    public function getSiteTitle()
    {
        if ($this->settings->enable_title) {
            return trim($this->settings->title);
        }
        return '';
    }

}
