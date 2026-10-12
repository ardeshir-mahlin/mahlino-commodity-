Class B2B Super Platform (Git Connected Edition)
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
        add_shortcode('
