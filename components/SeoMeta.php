<?php namespace Sensory5\Seo\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Request;
use Sensory5\Seo\models\Settings;
use URL;

class SeoMeta extends ComponentBase
{
    public $pagePointer;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $canonical_url;
    public $redirect_url;
    public $robot_index;
    public $robot_follow;
    public $hasBlog;

    public $ogTitle;
    public $ogUrl;
    public $ogDescription;
    public $ogSiteName;
    public $ogFbAppId;
    public $ogLocale;
    public $ogImage;

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

    public function onRun()
    {
        $this->generateMeta();
    }

    public function generateMeta()
    {
        if (!$this->page) { return; }

        if (method_exists($this->page, 'hasComponent')) {
            $this->pagePointer = $this->page;
        }
        else {
            $this->pagePointer = $this->page->page;
        }

        $callback = [$this, $this->getCallback()];

        $this->meta_title = $this->pagePointer["meta_title"] = empty($callback('meta_title')) ?
            $callback('title') :
            $callback('meta_title');

        $this->meta_description = $this->pagePointer["meta_description"] = $callback('meta_description');
        $this->meta_keywords = $this->pagePointer["meta_keywords"] = $callback('meta_keywords');
        $this->canonical_url = $this->pagePointer["canonical_url"] = $callback('canonical_url');
        $this->redirect_url = $this->pagePointer["redirect_url"] = $callback('redirect_url');
        $this->robot_follow = $this->pagePointer["robot_follow"] = $callback('robot_follow');
        $this->robot_index = $this->pagePointer["robot_index"] = $callback('robot_index');

        $settings = Settings::instance();

        if($settings->enable_og_tags)
        {
            $this->ogTitle = empty($callback('meta_title')) ? $callback('title') : $callback('meta_title');
            $this->ogDescription = $callback('meta_description');
            $this->ogUrl = empty($callback('canonical_url')) ? Request::url() : $callback('canonical_url');
            $this->ogSiteName = $settings->og_sitename;
            $this->ogFbAppId = $settings->og_fb_appid;
        }

        $this->renderPartial('@meta');
    }

    /**
     * Determine which type of page this is
     * and return the appropriate callback
     */
    private function getCallback()
    {
        if ($this->pagePointer->hasComponent('blogPost')) {
            return 'blogMeta';
        }
        elseif ($this->pagePointer->hasComponent('staticPage')) {
            return 'staticMeta';
        }
        else {
            return 'cmsMeta';
        }
    }


    /**
     * Get data from a page
     *
     * @param string $attribute
     * @return string
     */
    private function cmsMeta($attribute)
    {
        $result = \Event::fire('sensory5.seo.cmsMeta', [&$this->pagePointer, $attribute], true);
        return $result ?: trim($this->pagePointer->{$attribute});
    }

    /**
     * Get data from a view bag
     *
     * @param string $attribute
     * @return string
     */
    private function staticMeta($attribute)
    {
        return trim($this->pagePointer->getViewBag()->property($attribute));
    }

    /**
     * Get data from a blog post
     *
     * @param string $attribute
     * @return string
     */
    private function blogMeta($attribute)
    {
        return trim($this->pagePointer->post->{'s5_seo_'.$attribute});
    }

}
