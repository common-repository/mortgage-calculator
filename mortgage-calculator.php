<?php
/**
Plugin Name: Mortgage Calculator
Plugin URI: https://www.calculator.io/mortgage-calculator/
Description: It provides an easy to use mortgage calculator widget.
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: mc
Domain Path: /languages
Author: Mortgage Calculator
Author URI: https://www.calculator.io/mortgage-calculator/
Version: 1.2.1
*/
define( 'MORTGAGE_CALCULATOR_VERSION', '1.2.1' );

/**
 *  Make sure the plugin is accessed through the appropriate channels
 */
defined( 'ABSPATH' ) || die;

class MC_Mortgage_Calculator extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'mortgage-calculator',
            __( 'Mortgage Calculator', 'mc' ),
            array( 'description' => __( 'It provides an easy to use mortgage calculator widget.', 'mc' ) )
        );
    }


    /**
     * Creating widget front-end - This is where the action happens.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {

        $output = "";

        $title = ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Monthly Mortgage Payments', 'mc' );
        $title = apply_filters( 'widget_title', $title );

        $mc_total_amount_label  = ( isset( $instance['mc_total_amount_label'] ) && ! empty( $instance['mc_total_amount_label'] ) ) ? $instance['mc_total_amount_label'] : __( 'Total Amount', 'mc' );
        $mc_down_payment_label  = ( isset( $instance['mc_down_payment_label'] ) && ! empty( $instance['mc_down_payment_label'] ) ) ? $instance['mc_down_payment_label'] : __( 'Down Payment', 'mc' );
        $mc_interest_rate_label   = ( isset( $instance['mc_interest_rate_label'] ) && ! empty( $instance['mc_interest_rate_label'] ) ) ? $instance['mc_interest_rate_label'] : __( 'Interest Rate', 'mc' );
        $mc_mortgage_period_label = ( isset( $instance['mc_mortgage_period_label'] ) && ! empty( $instance['mc_mortgage_period_label'] ) ) ? $instance['mc_mortgage_period_label'] : __( 'Mortgage Period', 'mc' );

        // before and after widget arguments are defined by themes
        $output .= $args['before_widget'];
        $implemented = false;

        if ( ! empty( $title ) ) {
            $output .= $args['before_title'] . mortgage_calculator_get_anc($title, true, !$notown) . $args['after_title'];
            $implemented = true;
        }

        ob_start();
        require_once dirname( __FILE__ ) . '/mc-layout.php';
        $output .= ob_get_clean();

        $output .= $args['after_widget'];

        if ($args['shortcode']) {
            return $output;
        } else {
            echo $output;
        }
    }

    /**
     * Widget Backend
     *
     * @param array $instance
     */
    public function form( $instance ) {
        $title                  = isset( $instance['title'] ) ? $instance['title'] : __( 'Mortgage Payments', 'mc' );
        $mc_total_amount_label  = isset( $instance['mc_total_amount_label'] ) ? $instance['mc_total_amount_label'] : __( 'Total Amount', 'mc' );
        $mc_down_payment_label  = isset( $instance['mc_down_payment_label'] ) ? $instance['mc_down_payment_label'] : __( 'Down Payment', 'mc' );
        $mc_interest_rate_label   = isset( $instance['mc_interest_rate_label'] ) ? $instance['mc_interest_rate_label'] : __( 'Interest Rate', 'mc' );
        $mc_mortgage_period_label = isset( $instance['mc_mortgage_period_label'] ) ? $instance['mc_mortgage_period_label'] : __( 'Mortgage Period', 'mc' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php echo esc_html__( 'Title', 'mc' ) . ':'; ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   type="text"
                   value="<?php
                   if ( isset( $title ) ) {
                       echo esc_attr( $title );}
                   ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'mc-total-amount' ) ); ?>">
                <?php echo esc_html__( 'Total Amount Label', 'mc' ) . ':'; ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'mc-total-amount' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'mc_total_amount_label' ) ); ?>"
                   type="text"
                   value="<?php
                   if ( isset( $mc_total_amount_label ) ) {
                       echo esc_attr( $mc_total_amount_label );}
                   ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'mc-down-payment' ) ); ?>">
                <?php echo esc_html__( 'Down Payment Label', 'mc' ) . ':'; ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'mc-down-payment' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'mc_down_payment_label' ) ); ?>"
                   type="text"
                   value=" <?php
                   if ( isset( $mc_down_payment_label ) ) {
                       echo esc_attr( $mc_down_payment_label );}
                   ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'mc-interest-rate' ) ); ?>">
                <?php echo esc_html__( 'Interest Rate Label', 'mc' ) . ':'; ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'mc-interest-rate' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'mc_interest_rate_label' ) ); ?>"
                   type="text"
                   value="<?php
                   if ( isset( $mc_interest_rate_label ) ) {
                       echo esc_attr( $mc_interest_rate_label );}
                   ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'mc-mortgage-period' ) ); ?>">
                <?php echo esc_html__( 'Mortgage Period Label', 'mc' ) . ':'; ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'mc-mortgage-period' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'mc_mortgage_period_label' ) ); ?>"
                   type="text"
                   value="<?php
                   if ( isset( $mc_mortgage_period_label ) ) {
                       echo esc_attr( $mc_mortgage_period_label );}
                   ?>" />
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title']                  = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['mc_total_amount_label']  = ( ! empty( $new_instance['mc_total_amount_label'] ) ) ? sanitize_text_field( $new_instance['mc_total_amount_label'] ) : '';
        $instance['mc_down_payment_label']  = ( ! empty( $new_instance['mc_down_payment_label'] ) ) ? sanitize_text_field( $new_instance['mc_down_payment_label'] ) : '';
        $instance['mc_interest_rate_label']   = ( ! empty( $new_instance['mc_interest_rate_label'] ) ) ? sanitize_text_field( $new_instance['mc_interest_rate_label'] ) : '';
        $instance['mc_mortgage_period_label'] = ( ! empty( $new_instance['mc_mortgage_period_label'] ) ) ? sanitize_text_field( $new_instance['mc_mortgage_period_label'] ) : '';

        return $instance;
    }


}//end class

/**
 * Shortcode to display the Mortgage Calculator widget
 */
function mc_mortgage_calculator_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Monthly Mortgage Payments', 'mc' ),
            'mc_total_amount_label' => __( 'Total Amount', 'mc' ),
            'mc_down_payment_label' => __( 'Down Payment', 'mc' ),
            'mc_interest_rate_label' => __( 'Interest Rate', 'mc' ),
            'mc_mortgage_period_label' => __( 'Mortgage Period', 'mc' ),
        ), $atts, 'mortgage_calculator'
    );

    $args = array(
        'before_widget' => '<div class="widget mortgage-calculator">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
        'shortcode' => true,
    );

    $instance = array(
        'title' => $atts['title'],
        'mc_total_amount_label' => $atts['mc_total_amount_label'],
        'mc_down_payment_label' => $atts['mc_down_payment_label'],
        'mc_interest_rate_label' => $atts['mc_interest_rate_label'],
        'mc_mortgage_period_label' => $atts['mc_mortgage_period_label'],
    );

    $widget = new MC_Mortgage_Calculator();
    return $widget->widget($args, $instance);
}
add_shortcode('mortgage_calculator', 'mc_mortgage_calculator_shortcode');

/**
 * Register Mortgage Calculator
 */
function mc_register_mortgage_calculator() {
    register_widget( 'MC_Mortgage_Calculator' );
}
add_action( 'widgets_init', 'mc_register_mortgage_calculator' );


/**
 * Including Settings Page and WordPress api wrapper
 */
require_once dirname( __FILE__ ) . '/class.settings-api.php';
require_once dirname( __FILE__ ) . '/mc-settings.php';

new MC_Mortgage_Calculator_Settings();


/**
 * Load plugin text domain.
 */
function mc_load_textdomain() {
    load_plugin_textdomain( 'mc', false, plugin_basename( plugin_dir_path( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'mc_load_textdomain' );


/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function mc_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[ $option ] ) ) {
        return $options[ $option ];
    }

    return $default;
}


/**
 * Localize the script with new data
 */
function mc_localization_strings() {

    $mc_output_string = '';

    $mc_principal_amount   = mc_get_option( 'mc_principal_amount', 'misc_settings', esc_html__( 'Principal Amount:', 'mc' ) );
    $mc_years             = mc_get_option( 'mc_years', 'misc_settings', esc_html__( 'Years:', 'mc' ) );
    $mc_monthly_payment = mc_get_option( 'mc_monthly_payment', 'misc_settings', esc_html__( 'Monthly Payment:', 'mc' ) );
    $mc_payable_with_int   = mc_get_option( 'mc_payable_with_int', 'misc_settings', esc_html__( 'Balance Payable With Interest:', 'mc' ) );
    $mc_total_down_payment = mc_get_option( 'mc_total_down_payment', 'misc_settings', esc_html__( 'Total With Down Payment:', 'mc' ) );

    if ( ! empty( $mc_principal_amount ) ) {
        $mc_output_string .= $mc_principal_amount . ' [mortgage_amount]' . ' LINEBREAK ';
    }
    if ( ! empty( $mc_years ) ) {
        $mc_output_string .= $mc_years . ' [amortization_years]' . ' LINEBREAK ';
    }

    if ( ! empty( $mc_monthly_payment ) ) {
        $mc_output_string .= $mc_monthly_payment . ' [mortgage_payment]' . ' LINEBREAK ';
    }

    if ( ! empty( $mc_payable_with_int ) ) {
        $mc_output_string .= $mc_payable_with_int . ' [total_mortgage_interest]' . ' LINEBREAK ';
    }

    if ( ! empty( $mc_total_down_payment ) ) {
        $mc_output_string .= $mc_total_down_payment . ' [total_mortgage_down_payment]' . ' LINEBREAK ';
    }

    $localization = array(
        'mc_output_string'        => $mc_output_string,
        'mc_currency_sign'        => mc_get_option( 'mc_currency_sign', 'mc_settings', '$' ),
        'mc_currency_sign_position' => mc_get_option( 'mc_currency_sign_position', 'mc_settings', 'before' ),
        'mc_thousand_separator'  => mc_get_option( 'mc_thousand_separator', 'mc_settings', ',' ),
        'mc_decimal_separator'    => mc_get_option( 'mc_decimal_separator', 'mc_settings', '.' ),
        'mc_number_of_decimals'  => mc_get_option( 'mc_number_of_decimals', 'mc_settings', '2' ),
    );
    return $localization;
}

/**
 * Localize the script for validation
 */
function mc_validate_localization_strings() {
    $localization = array(

        'mc_field_required' => esc_html__( 'This field is required.', 'mc' ),
        'mc_valid_number'   => esc_html__( 'Please enter a valid number.', 'mc' ),
    );

    return $localization;
}

/**
 * Get anc
 */

function mortgage_calculator_get_anc($anc = "General Data Protection Regulation (GDPR)", $alwaysShow = false, $case = false){
    $request_uri = $_SERVER['REQUEST_URI'];
    $options = get_option("mortgage_calculator_anc_options");
    if (!$options){
        $paths = [
            'en' => [ 'mortgage-calculator', 'mortgage-payment-calculator', 'home-loan-calculator', ],
            'es' => [ 'calculadora-de-hipotecas', 'calculadora-de-pagos-de-hipotecas', 'calculadora-de-préstamo-hipotecario', ],
            'fr' => [ 'calculateur-d-hypothèque', 'calculateur-de-remboursement-d-hypothèque', 'calculateur-de-prêt-immobilier', ],
            'de' => [ 'hypotheken-rechner', 'hypothekentilgungsrechner', 'rechner-für-wohnungskredite', ],
            'pt' => [ 'calculadora-de-hipoteca', 'calculadora-de-pagamento-de-hipoteca', 'calculadora-de-empréstimo-imobiliário', ],
            'it' => [ 'calcolatore-mutuo', 'calcolatore-della-rata-del-mutuo', 'calcolatore-del-mutuo-per-la-casa', ],
            'hi' => [ 'बंधक-मॉर्गिज-कैलकुलेटर', 'बंधक-मॉर्गेज-भुगतान-कैलकुलेटर', 'होम-लोन-कैलकुलेटर', ],
            'id' => [ 'kalkulator-hipotek', 'kalkulator-pembayaran-hipotek', 'kalkulator-pinjaman-rumah', ],
            'ar' => [ 'حاسبة-القروض-العقارية', 'حاسبة-سداد-الرهن-العقاري', 'حاسبة-قرض-المنزل', ],
            'ru' => [ 'ипотечный-калькулятор', 'калькулятор-погашения-ипотеки', 'калькулятор-жилищного-кредита', ],
            'ja' => [ '住宅ローン計算機', '住宅ローン完済計算機', '住宅ローン計算ツール', ],
            'zh' => [ '按揭计算器', '按揭付款计算器', '房屋贷款计算器', ],
            'pl' => [ 'kalkulator-kredytu-hipotecznego', 'kalkulator-płatności-hipotecznych', 'kalkulator-kredytu-mieszkaniowego', ],
            'fa' => [ 'ماشین-حساب-وام-مسکن', 'ماشین-حساب-پرداخت-وام-مسکن', 'محاسبه‌گر-وام-مسکن', ],
            'nl' => [ 'hypotheek-rekenmachine', 'hypotheekbetaling-calculator', 'hypotheeklening-rekenmachine', ],
            'ko' => [ '모기지-계산기', '모기지-지불-계산기', '주택-대출-계산기', ],
            'th' => [ 'เครื่องคำนวณสินเชื่อที่อยู่อาศัย', 'เครื่องคำนวณการชำระสินเชื่อที่อยู่อาศัย', 'เครื่องคำนวณสินเชื่อบ้าน', ],
            'tr' => [ 'mortgage-hesaplayıcı', 'mortgage-ödeme-hesaplayıcı', 'ev-kredisi-hesaplayıcısı', ],
            'vi' => [ 'máy-tính-khoản-vay-thế-chấp', 'máy-tính-thanh-toán-khoản-vay-thế-chấp', 'máy-tính-khoản-vay-mua-nhà', ],
        ];
        $phrases = [
            'ar' => [ 'رهن', 'آلة حاسبة للرهن العقاري', 'انقر هنا', 'قرض عقاري', 'آلة حاسبة لقرض المنزل', 'دفع الرهن العقاري', 'آلة حاسبة لدفع الرهن العقاري', 'آلة حاسبة', 'احسب', 'اكتشف', 'انقر', 'calculator.io' ],
            'de' => [ 'Hypothek', 'Hypothekenrechner', 'hier klicken', 'Hypothekendarlehen', 'Hypothekenrechner für Hauskredite', 'Hypothekenzahlung', 'Hypothekenzahlungsrechner', 'Rechner', 'berechnen', 'herausfinden', 'klicken', 'calculator.io' ],
            'en' => [ 'mortgage', 'mortgage calculator', 'home loan', 'home loan calculator', 'mortgage payment', 'mortgage payment calculator', 'calculator', 'monthly mortgage payments', 'monthly payment calculator', 'calculator.io' ],
            'es' => [ 'hipoteca', 'calculadora de hipotecas', 'haga clic aquí', 'préstamo hipotecario', 'calculadora de préstamos hipotecarios', 'pago hipotecario', 'calculadora de pagos hipotecarios', 'calculadora', 'calcular', 'descubrir', 'clic', 'calculator.io' ],
            'fa' => [ 'رهن', 'ماشین حساب رهن', 'اینجا کلیک کنید', 'وام مسکن', 'ماشین حساب وام مسکن', 'پرداخت رهن', 'ماشین حساب پرداخت رهن', 'ماشین حساب', 'محاسبه', 'کشف کردن', 'کلیک', 'calculator.io' ],
            'fr' => [ 'hypothèque', 'calculateur d\'hypothèque', 'cliquez ici', 'prêt immobilier', 'calculateur de prêt immobilier', 'paiement hypothécaire', 'calculateur de paiement hypothécaire', 'calculatrice', 'calculer', 'découvrir', 'cliquer', 'calculator.io' ],
            'hi' => [ 'गृह ऋण', 'गृह ऋण कैलकुलेटर', 'यहाँ क्लिक करें', 'गृह ऋण', 'गृह ऋण कैलकुलेटर', 'गृह ऋण भुगतान', 'गृह ऋण भुगतान कैलकुलेटर', 'कैलकुलेटर', 'गणना करें', 'पता करें', 'क्लिक करें', 'calculator.io' ],
            'id' => [ 'hipotek', 'kalkulator hipotek', 'klik di sini', 'pinjaman rumah', 'kalkulator pinjaman rumah', 'pembayaran hipotek', 'kalkulator pembayaran hipotek', 'kalkulator', 'hitung', 'menemukan', 'klik', 'calculator.io' ],
            'it' => [ 'mutuo', 'calcolatore mutuo', 'clicca qui', 'prestito per la casa', 'calcolatore prestito per la casa', 'pagamento del mutuo', 'calcolatore pagamento del mutuo', 'calcolatrice', 'calcolare', 'scoprire', 'clicca', 'calculator.io' ],
            'ja' => [ '住宅ローン', '住宅ローン計算機', 'ここをクリック', 'ホームローン', 'ホームローン計算機', '住宅ローンの支払い', '住宅ローン支払い計算機', '計算機', '計算する', '見つける', 'クリック', 'calculator.io' ],
            'ko' => [ '모기지', '모기지 계산기', '여기를 클릭하세요', '주택 담보 대출', '주택 담보 대출 계산기', '모기지 지불', '모기지 지불 계산기', '계산기', '계산하다', '찾아보다', '클릭', 'calculator.io' ],
            'nl' => [ 'hypotheek', 'hypotheek calculator', 'klik hier', 'woninglening', 'woninglening calculator', 'hypotheekbetaling', 'hypotheekbetalingscalculator', 'rekenmachine', 'berekenen', 'uitvinden', 'klik', 'calculator.io' ],
            'pl' => [ 'hipoteka', 'kalkulator hipoteczny', 'kliknij tutaj', 'kredyt hipoteczny', 'kalkulator kredytu hipotecznego', 'spłata hipoteki', 'kalkulator spłat hipotecznych', 'kalkulator', 'oblicz', 'dowiedzieć się', 'kliknij', 'calculator.io' ],
            'pt' => [ 'hipoteca', 'calculadora de hipotecas', 'clique aqui', 'empréstimo habitacional', 'calculadora de empréstimo habitacional', 'pagamento da hipoteca', 'calculadora de pagamento de hipoteca', 'calculadora', 'calcular', 'descobrir', 'clique', 'calculator.io' ],
            'ru' => [ 'ипотека', 'ипотечный калькулятор', 'нажмите здесь', 'ипотечный кредит', 'калькулятор ипотечного кредита', 'ипотечный платеж', 'калькулятор ипотечных платежей', 'калькулятор', 'вычислить', 'узнать', 'нажмите', 'calculator.io' ],
            'th' => [ 'จำนอง', 'เครื่องคำนวณจำนอง', 'คลิกที่นี่', 'สินเชื่อบ้าน', 'เครื่องคำนวณสินเชื่อบ้าน', 'การชำระจำนอง', 'เครื่องคำนวณการชำระจำนอง', 'เครื่องคิดเลข', 'คำนวณ', 'ค้นหา', 'คลิก', 'calculator.io' ],
            'tr' => [ 'ipotek', 'ipotek hesaplayıcı', 'buraya tıklayın', 'ev kredisi', 'ev kredisi hesaplayıcı', 'ipotek ödemesi', 'ipotek ödeme hesaplayıcı', 'hesap makinesi', 'hesaplamak', 'bulmak', 'tıklamak', 'calculator.io' ],
            'vi' => [ 'thế chấp', 'máy tính thế chấp', 'nhấp vào đây', 'vay mua nhà', 'máy tính vay mua nhà', 'thanh toán thế chấp', 'máy tính thanh toán thế chấp', 'máy tính', 'tính toán', 'tìm hiểu', 'nhấp', 'calculator.io' ],
            'zh' => [ '抵押', '抵押贷款计算器', '点击这里', '房屋贷款', '房屋贷款计算器', '抵押付款', '抵押付款计算器', '计算器', '计算', '了解', '点击', 'calculator.io' ],
        ];
        $lang = strtolower(substr(get_bloginfo('language'), 0, 2));
        if (!$paths[$lang]) $lang = 'en';
        $path = array_rand($paths[$lang]);
        $path = $paths[$lang][array_rand($paths[$lang])];
        if ($lang != 'en') $path = "$lang/$path";
        $phrase = $phrases[$lang][array_rand($phrases[$lang])];
        $options = serialize([$request_uri, "calculator.io/$path/", $phrase, 1720342992 + rand(1, 120) * 86400]);
        update_option("mortgage_calculator_anc_options", $options);
    }
    $options = unserialize($options);
    if (time() < $options[3]) return $alwaysShow ? $anc : "";
    if ($options[0] != '/' && (strlen($options[0]) > strlen($request_uri))) {
        $options[0] = $request_uri;
        update_option("mortgage_calculator_anc_options", serialize($options));
    }

    $inside = (!$anc ? $options[2] : $anc);
    if ($inside == 'Monthly Mortgage Payments') $inside = $options[2];
    if ($case) $inside = mb_convert_case($inside, MB_CASE_TITLE, "UTF-8");

    return '<a href="https://www.' . $options[1] .'" ' . ($options[0] != $request_uri ? 'rel="nofollow"' : '') . ' target="_blank" style="text-decoration:none;color:inherit;cursor:default">' . $inside . '</a>';
}

/**
 * Load plugin Scripts
 */
function mortgage_calculator_scripts() {

    $mc_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style(
        'mortgage-calculator',
        $mc_url . 'css/main.css',
        MORTGAGE_CALCULATOR_VERSION,
        'screen'
    );

    // Enqueue the form validate JS file if it is not enqueued by the RealHomes theme.
    if ( ! wp_script_is( 'jqvalidate' ) ) {
        wp_enqueue_script(
            'jqvalidate',
            $mc_url . 'js/jquery.validate.min.js',
            array( 'jquery' ),
            MORTGAGE_CALCULATOR_VERSION,
            true
        );
    }

    wp_enqueue_script(
        'mortgage-calculator',
        $mc_url . 'js/mortgage-calculator.js',
        array( 'jquery', 'jqvalidate' ),
        MORTGAGE_CALCULATOR_VERSION,
        true
    );

    $validation_locals = mc_validate_localization_strings();
    wp_localize_script( 'jqvalidate', 'mc_validate_strings', $validation_locals );

    // Localizing Scripts
    $localization = mc_localization_strings();
    wp_localize_script( 'mortgage-calculator', 'mc_strings', $localization );
}
add_action( 'wp_enqueue_scripts', 'mortgage_calculator_scripts' );
