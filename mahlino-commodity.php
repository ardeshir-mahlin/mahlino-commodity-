<?php
/**
 * Plugin Name: Mahlino World-Class B2B Super Platform (Git Connected Edition)
 * Plugin URI: https://github.com/ardeshir-mahlin/mahlino-commodity-
 * Description: سوپرپلتفرم بورس بین‌المللی کالا و ارتش کارگزاران ماهلینو؛ متصل به گیت‌هاب جهت به‌روزرسانی خودکار ۱-کلیکی.
 * Version: 18.2.0
 * Author: Mahlino Global Technologies
 * Author URI: https://mahlino.com
 * GitHub Plugin URI: ardeshir-mahlin/mahlino-commodity-
 * Primary Branch: main
 */

if (!defined('ABSPATH')) exit;

class Mahlino_Commodity_Subscription_Core {

    const BRAND_NAME  = 'Mahlino Commodity';
    const BRAND_TITLE = 'بورس بین‌المللی کالا و شبکه کارگزاران ماهلینو';
    const CARD_NUMBER = '5859-8310-4799-6805';
    const CARD_HOLDER = 'اردشیر رحمتی';
    const CARD_BANK   = 'بانک تجارت';
    const ZARINPAL_MERCHANT = 'c7fc949a-d62c-45cf-82ed-d4b6d9be96e5';

    public function __construct() {
        add_action('init', [$this, 'init_engine']);
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        add_shortcode('mahlino_app', [$this, 'render_platform']);
        add_shortcode('mahlino_home_view', [$this, 'render_platform']);

        add_filter('pre_option_show_on_front', function() { return 'page'; });
        add_filter('pre_option_page_on_front', [$this, 'get_portal_page_id']);
    }

    public function init_engine() {
        register_post_type('mahlino_deal', [
            'labels' => ['name' => 'بارهای بورس کالا', 'singular_name' => 'محموله کالا', 'add_new' => 'افزودن محموله'],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-chart-area',
            'supports' => ['title', 'editor', 'thumbnail']
        ]);

        register_post_type('mahlino_broker', [
            'labels' => ['name' => 'ارتش کارگزاران', 'singular_name' => 'کارگزار'],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'custom-fields']
        ]);
    }

    public function load_assets() {
        wp_register_style('mahlino-font-vazir', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
        wp_enqueue_style('mahlino-font-vazir');
    }

    public function get_portal_page_id() {
        $page = get_page_by_path('market');
        if (!$page) {
            $pid = wp_insert_post([
                'post_title'   => self::BRAND_TITLE,
                'post_name'    => 'market',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '[mahlino_app]'
            ]);
            return $pid;
        }
        return $page->ID;
    }

    public function render_platform() {
        $q = new WP_Query([
            'post_type'      => 'mahlino_deal',
            'posts_per_page' => 50,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);

        ob_start(); ?>
        <div id="mahlino-app-root" style="direction:rtl; font-family:'Vazirmatn', sans-serif; background:#f8fafc; color:#0f172a; max-width:1200px; margin:20px auto; padding:20px; border-radius:16px;">
            <div style="background:#0b1329; color:#fff; padding:18px; border-radius:12px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h2 style="margin:0; font-size:20px;"><?php echo self::BRAND_NAME; ?></h2>
                    <span style="font-size:12px; color:#94a3b8;"><?php echo self::BRAND_TITLE; ?></span>
                </div>
            </div>
            <div style="margin-top:20px; display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:15px;">
                <?php if($q->have_posts()): while($q->have_posts()): $q->the_post(); ?>
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:15px;">
                        <h4 style="margin:0 0 10px;"><?php the_title(); ?></h4>
                        <p style="font-size:13px; color:#64748b;"><?php echo wp_trim_words(get_the_content(), 15); ?></p>
                    </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }
}

new Mahlino_Commodity_Subscription_Core();
