<?php
/**
 * Plugin Name: Mahlino World-Class B2B Super Platform
 * Plugin URI: https://github.com/ardeshir-mahlin/mahlino-commodity-
 * Description: سوپرپلتفرم بورس بین‌المللی کالا و ارتش کارگزاران ماهلینو؛ قدرتمند، امن و با به‌روزرسانی خودکار ۱-کلیکی.
 * Version: 18.4.0
 * Author: Mahlino Global Technologies
 * Author URI: https://mahlino.com
 */

if (!defined('ABSPATH')) exit;

// موتور دریافت خودکار آپدیت ۱-کلیکی از گیت‌هاب
add_filter('site_transient_update_plugins', function($transient) {
    if (empty($transient->checked)) return $transient;

    $remote_url = 'https://raw.githubusercontent.com/ardeshir-mahlin/mahlino-commodity-/main/mahlino-commodity.php';
    $response = wp_remote_get($remote_url, ['timeout' => 15]);

    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $content = wp_remote_retrieve_body($response);
        if (preg_match('/Version:\s*([0-9.]+)/i', $content, $matches)) {
            $new_ver = trim($matches[1]);
            $current_ver = '18.3.0';

            if (version_compare($current_ver, $new_ver, '<')) {
                $obj = new stdClass();
                $obj->slug = 'mahlino-v15-master';
                $obj->new_version = $new_ver;
                $obj->url = 'https://github.com/ardeshir-mahlin/mahlino-commodity-';
                $obj->package = 'https://github.com/ardeshir-mahlin/mahlino-commodity-/archive/refs/heads/main.zip';

                $transient->response['mahlino-v15-master/mahlino-v15-master.php'] = $obj;
            }
        }
    }
    return $transient;
});

class Mahlino_Commodity_Subscription_Core {
    const BRAND_NAME  = 'Mahlino Commodity';
    const BRAND_TITLE = 'تالار مبادلات بورس بین‌المللی کالا و شبکه کارگزاران رسمی';

    public function __construct() {
        add_action('init', [$this, 'init_engine']);
        add_shortcode('mahlino_app', [$this, 'render_platform']);
        add_shortcode('mahlino_home_view', [$this, 'render_platform']);
    }

    public function init_engine() {
        register_post_type('mahlino_deal', [
            'labels' => ['name' => 'بارهای بورس کالا', 'singular_name' => 'محموله کالا', 'add_new' => 'افزودن محموله'],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-chart-area',
            'supports' => ['title', 'editor', 'thumbnail']
        ]);
    }

    public function render_platform() {
        $q = new WP_Query([
            'post_type'      => 'mahlino_deal',
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);

        ob_start(); ?>
        <!-- استایل مدرن، باابهت، پرانرژی و اعتمادبخش ماهلینو -->
        <style>
            @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
            .mahlino-wrapper {
                font-family: 'Vazirmatn', sans-serif;
                direction: rtl;
                background: linear-gradient(135deg, #090e1a 0%, #101c36 50%, #081124 100%);
                border-radius: 24px;
                padding: 30px;
                color: #ffffff;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 215, 0, 0.25);
                max-width: 1240px;
                margin: 20px auto;
            }
            .mahlino-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                padding-bottom: 24px;
                margin-bottom: 30px;
                flex-wrap: wrap;
                gap: 15px;
            }
            .mahlino-title {
                font-size: 26px;
                font-weight: 900;
                background: linear-gradient(90deg, #f9fafb, #fbbf24);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin: 0;
            }
            .mahlino-subtitle {
                color: #94a3b8;
                font-size: 14px;
                margin-top: 5px;
                display: block;
            }
            .mahlino-badge-status {
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                color: #ffffff;
                padding: 10px 18px;
                border-radius: 50px;
                font-size: 13px;
                font-weight: bold;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .mahlino-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 22px;
            }
            .mahlino-card {
                background: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 18px;
                padding: 24px;
                backdrop-filter: blur(14px);
                transition: all 0.3s ease;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
            .mahlino-card:hover {
                transform: translateY(-5px);
                border-color: #fbbf24;
                box-shadow: 0 15px 35px rgba(251, 191, 36, 0.15);
            }
            .mahlino-card h4 {
                color: #f8fafc;
                font-size: 18px;
                margin: 0 0 12px 0;
                line-height: 1.5;
            }
            .mahlino-card p {
                color: #cbd5e1;
                font-size: 13.5px;
                line-height: 1.8;
                margin-bottom: 20px;
            }
            .mahlino-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(0, 0, 0, 0.25);
                padding: 12px 16px;
                border-radius: 12px;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
            }
            .mahlino-security-tag {
                color: #10b981;
                font-size: 12.5px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .mahlino-btn-action {
                background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
                color: #000;
                font-weight: 800;
                padding: 8px 16px;
                border-radius: 8px;
                text-decoration: none;
                font-size: 12px;
                transition: opacity 0.2s;
            }
            .mahlino-btn-action:hover {
                opacity: 0.9;
            }
        </style>

        <div class="mahlino-wrapper">
            <div class="mahlino-header">
                <div>
                    <h2 class="mahlino-title"><?php echo self::BRAND_NAME; ?></h2>
                    <span class="mahlino-subtitle"><?php echo self::BRAND_TITLE; ?></span>
                </div>
                <div class="mahlino-badge-status">
                    <span>● تالار امن متصل به شبکه هوشمند</span>
                </div>
            </div>

            <div class="mahlino-grid">
                <?php if($q->have_posts()): while($q->have_posts()): $q->the_post(); ?>
                    <div class="mahlino-card">
                        <h4><?php the_title(); ?></h4>
                        <p><?php echo wp_trim_words(get_the_content(), 22, '...'); ?></p>
                        <div class="mahlino-card-footer">
                            <span class="mahlino-security-tag">✓ تضمین اصالت و بازرسی</span>
                            <a href="<?php the_permalink(); ?>" class="mahlino-btn-action">مشاهده جزئیات بار</a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else: ?>
                    <div class="mahlino-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <h4 style="color:#fbbf24;">تالار بورس در حال بارگذاری قراردادهای جدید</h4>
                        <p>به زودی بارهای بین‌المللی تایید شده در این بخش به نمایش درمی‌آیند.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }
}

new Mahlino_Commodity_Subscription_Core();
