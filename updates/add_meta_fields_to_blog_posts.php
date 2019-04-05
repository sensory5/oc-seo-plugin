<?php namespace Sensory5\Seo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;
class AddMetaFieldsToBlogPosts extends Migration
{

    public function up()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::table('rainlab_blog_posts', function($table)
            {
                $table->string('s5_seo_meta_title')->nullable();
                $table->string('s5_seo_meta_description')->nullable();
                $table->string('s5_seo_meta_keywords')->nullable();
                $table->string('s5_seo_canonical_url')->nullable();
                $table->string('s5_seo_redirect_url')->nullable();
                $table->string('s5_seo_robot_index')->nullable();
                $table->string('s5_seo_robot_follow')->nullable();
            });
        }
    }

    public function down()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::table('rainlab_blog_posts', function($table)
            {
                $table->dropColumn('s5_seo_meta_title');
                $table->dropColumn('s5_seo_meta_description');
                $table->dropColumn('s5_seo_meta_keywords');
                $table->dropColumn('s5_seo_canonical_url');
                $table->dropColumn('s5_seo_redirect_url');
                $table->dropColumn('s5_seo_robot_index');
                $table->dropColumn('s5_seo_robot_follow');
            });
        }

    }

}