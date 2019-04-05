Sensory 5 - SEO Extension
=============

_Originally forked from: https://github.com/anand-patel/oc-seo-extension_

Modified and adjusted for use with Sensory 5 sites.

###Inject SEO fields to CMS Pages, Static Pages and Blog.

This plugin add SEO fields to CMS Pages, Static Pages and Blog, and for using it you simply need to drop component on layout/page.

currently included fields:
* Meta Title
* Meta Description
* Meta Keywords
* Canonical URL
* Meta Redirect to other URL
* Robot Index & Follow
* Open Graph(og) Tags added for better sharing on social networking sites like Facebook
* Settings added in backend to configure meta and Open Graph tags

#Documentation

#####**Installation**

This plugin is part of the Sensory 5 suite of plugins available at https://github.com/sensory5

There is only one component required to make this plugin work:

Drop this component into the layout`s head section

For example:

``````````````````
    <html>
        <head>
            {% component 'SeoMeta' %}
        </head>
        <body>
           {% page %}
        </body>
    </html>
``````````````````

####Configuration

To configure this Plugin goto Backend *System* then find *My Settings* in left side bar, then click on *SEO Extension* , you will get Configuration options.(refer screenshots)
