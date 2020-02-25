<?php namespace Sensory5\Seo;

use Cms\Classes\Theme;
use Sensory5\Seo\Classes\Helper;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

/**
 * Sensory 5 seo plugin
 */
class Plugin extends PluginBase
{
    /**
     * Return information about plugin
     */
    public function pluginDetails()
    {
        return [
            'name' => 'S5 SEO Extension',
            'description' => 'Provide SEO Extensions to October CMS Pages, Static Pages, Blog post',
            'author' => 'Sensory 5',
            'icon' => 'icon-search'
        ];
    }

    /**
     * Register the component with October
     */
    public function boot()
    {
        \Event::listen('backend.form.extendFields', function($widget) {

            if (PluginManager::instance()->hasPlugin('RainLab.Pages') &&
                $widget->model instanceof \RainLab\Pages\Classes\Page) {
                $widget->addTabFields([
                    'viewBag[meta_keywords]' => [
                        'label' => 'sensory5.seo::lang.editor.meta_keywords',
                        'type' => 'textarea',
                        'size' => 'tiny',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    'viewBag[canonical_url]' => [
                        'label' => 'sensory5.seo::lang.editor.canonical_url',
                        'type' => 'text',
                        'span' => 'left',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    'viewBag[redirect_url]' => [
                        'label' => 'sensory5.seo::lang.editor.redirect_url',
                        'type' => 'text',
                        'span' => 'right',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    'viewBag[robot_index]' => [
                        'label' => 'sensory5.seo::lang.editor.robot_index',
                        'type' => 'dropdown',
                        'span' => 'left',
                        'tab' => 'cms::lang.editor.meta',
                        'options' => $this->getIndexOptions()
                    ],
                    'viewBag[robot_follow]' => [
                        'label' => 'sensory5.seo::lang.editor.robot_follow',
                        'type' => 'dropdown',
                        'span' => 'right',
                        'tab' => 'cms::lang.editor.meta',
                        'options' => $this->getFollowOptions()
                    ],
                ]);
            }

            if (PluginManager::instance()->hasPlugin('RainLab.Blog') &&
                $widget->model instanceof \RainLab\Blog\Models\Post) {
                $widget->addSecondaryTabFields([
                    's5_seo_meta_title' => [
                        'label' => 'sensory5.seo::lang.editor.meta_title',
                        'type' => 'text',
                        'span' => 'full',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    's5_seo_meta_description' => [
                        'label' => 'sensory5.seo::lang.editor.meta_description',
                        'type' => 'textarea',
                        'size' => 'tiny',
                        'span' => 'full',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    's5_seo_meta_keywords' => [
                        'label' => 'sensory5.seo::lang.editor.meta_keywords',
                        'type' => 'textarea',
                        'size' => 'tiny',
                        'span' => 'full',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    's5_seo_canonical_url' => [
                        'label' => 'sensory5.seo::lang.editor.canonical_url',
                        'type' => 'text',
                        'span' => 'left',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    's5_seo_redirect_url' => [
                        'label' => 'sensory5.seo::lang.editor.redirect_url',
                        'type' => 'text',
                        'span' => 'right',
                        'tab' => 'cms::lang.editor.meta'
                    ],
                    's5_seo_robot_index' => [
                        'label' => 'sensory5.seo::lang.editor.robot_index',
                        'type' => 'dropdown',
                        'span' => 'left',
                        'tab' => 'cms::lang.editor.meta',
                        'options' => $this->getIndexOptions(),
                        'default' => 'index'
                    ],
                    's5_seo_robot_follow' => [
                        'label' => 'sensory5.seo::lang.editor.robot_follow',
                        'type' => 'dropdown',
                        'span' => 'right',
                        'tab' => 'cms::lang.editor.meta',
                        'options' => $this->getFollowOptions(),
                        'default' => 'follow'
                    ],
                ]);
            }

            if (!$widget->model instanceof \Cms\Classes\Page) {
                return;
            }

            if (!($theme = Theme::getEditTheme())) {
                return;
            }

            $widget->addTabFields([
                'settings[meta_keywords]' => [
                    'label' => 'sensory5.seo::lang.editor.meta_keywords',
                    'type' => 'textarea',
                    'size' => 'tiny',
                    'span' => 'full',
                    'tab' => 'cms::lang.editor.meta'
                ],
                'settings[canonical_url]' => [
                    'label' => 'sensory5.seo::lang.editor.canonical_url',
                    'type' => 'text',
                    'span' => 'left',
                    'tab' => 'cms::lang.editor.meta'
                ],
                'settings[redirect_url]' => [
                    'label' => 'sensory5.seo::lang.editor.redirect_url',
                    'type' => 'text',
                    'span' => 'right',
                    'tab' => 'cms::lang.editor.meta'
                ],
                'settings[robot_index]' => [
                    'label' => 'sensory5.seo::lang.editor.robot_index',
                    'type' => 'dropdown',
                    'span' => 'left',
                    'tab' => 'cms::lang.editor.meta',
                    'options' => $this->getIndexOptions()
                ],
                'settings[robot_follow]' => [
                    'label' => 'sensory5.seo::lang.editor.robot_follow',
                    'type' => 'dropdown',
                    'span' => 'right',
                    'tab' => 'cms::lang.editor.meta',
                    'options' => $this->getFollowOptions()
                ],
            ]);

        });

        \Event::listen('cms.page.end', function($controller) {

            if (!$controller->getLayout()->hasComponent('SeoMeta')) { return; }

            $component = $controller->getLayout()->components['SeoMeta'];

            if ($component) {
                $component->generateMeta();
            }

        });
    }


    /**
     * Register components used in the plugin
     */
    public function registerComponents()
    {
        return [
            'Sensory5\Seo\Components\SeoMeta' => 'SeoMeta' ,
            'Sensory5\Seo\Components\SeoEndBody' => 'SeoEndBody'
        ];
    }

    /**
     * Register settings used in the plugin
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'sensory5.seo::lang.settings.label',
                'description' => 'sensory5.seo::lang.settings.description',
                'icon' => 'icon-search',
                'category' => 'Sensory 5',
                'permissions' => ['sensory5.seo.settings.edit'],
                'class' => 'Sensory5\Seo\Models\Settings',
                'order' => 100
            ]
        ];
    }

    /**
     * Register additional twig markup used in the plugin
     */
    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'seoTitle' => [$this, 'generateTitle'],
                'seoCanonicalUrl' => [$this, 'generateCanonicalUrl'],
                'seoOtherMetaTags' => [$this, 'generateOtherMetaTags'],
                'seoEndBodyTags' => [$this, 'generateEndBodyTags']
            ],
            'functions' => [
                'seoSiteTitle' => [$this, 'getSiteTitle']
            ]
        ];
    }

    /**
     * Register permissions for the plugin
     */
    public function registerPermissions()
    {
        return [
            'sensory5.seo.settings.edit' => [
                'label' => 'sensory5.seo::lang.settings.permissions.settings_edit',
                'tab' => 'sensory5.seo::lang.plugin.name'
            ]
        ];
    }

    /**
     * Generate title
     *
     * @param string $title
     * @return string
     */
    public function generateTitle($title)
    {
        $helper = new Helper();
        return $helper->generateTitle($title);
    }

    /**
     * Generate canonical url
     *
     * @param string $url
     * @return string
     */
    public function generateCanonicalUrl($url)
    {
        $helper = new Helper();
        return $helper->generateCanonicalUrl($url);
    }

    /**
     * Generate other meta tags
     *
     * @return string
     */
    public function generateOtherMetaTags()
    {
        $helper = new Helper();
        return $helper->generateOtherMetaTags();
    }

    /**
     * Generate end body tags
     *
     * @return string
     */
    public function generateEndBodyTags()
    {
        $helper = new Helper();
        return $helper->generateEndBodyTags();
    }

    /**
     * Get the site title
     */
    public function getSiteTitle()
    {
        $helper = new Helper();
        return $helper->getSiteTitle();
    }

    public function getIndexOptions()
    {
        return ["index"=>"index","noindex"=>"noindex"];
    }

    public function getFollowOptions()
    {
        return ["follow"=>"follow","nofollow"=>"nofollow"];
    }
}

