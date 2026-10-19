<?php
/**
 * Legal pages (privacy policy, user agreement) and the site-wide choices their
 * text relies on.
 *
 * The page bodies live in hidden patterns (page-privacy / page-terms) and carry
 * placeholders — {site_name}, {site_domain}, {site_email}, ... — that are
 * resolved at render time, not baked in at provisioning. A cloned network site
 * or a domain move therefore needs no page edits, and an editor who materialises
 * the pattern in the block editor still sees where a value is substituted.
 *
 * Comments are closed theme-wide: the privacy policy promises the site stores
 * nothing about a visitor beyond service cookies, and core comments would
 * collect name, e-mail and IP.
 *
 * Blocks with the class `ac-if-analytics` render, and the cookie notice shows,
 * only when the site runs analytics: the «Сервисы аналитики» checkbox in
 * Settings → General (ticked by default), or automatically when partner-catalog has a Metrika
 * counter id. The wording never names a service — a site may add pixels or
 * other counters at any time.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Agent_Catalog_Legal {

	/** Provisioning roles (Agent_Catalog_Provision::MARKER values) of the legal pages. */
	const ROLES = array( 'privacy', 'terms' );

	/** Settings → General checkbox: the site runs analytics (pixels, counters). */
	const ANALYTICS_OPTION = 'agent_catalog_analytics';

	/** localStorage key remembering the dismissed cookie notice (listed in policy 3.7). */
	const NOTICE_KEY = 'ac-cookie-notice';

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register_setting' ) );
		add_action( 'wp_footer', array( __CLASS__, 'cookie_notice' ) );
		add_filter( 'comments_open', '__return_false', 20 );
		add_filter( 'pings_open', '__return_false', 20 );
		add_filter( 'comments_array', '__return_empty_array', 20 );

		add_filter( 'the_content', array( __CLASS__, 'fill_placeholders' ), 20 );
		add_filter( 'render_block', array( __CLASS__, 'filter_conditional' ), 10, 2 );
	}

	/** Host of home_url() without a leading "www.". */
	public static function domain() {
		$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
		return preg_replace( '/^www\./i', '', $host );
	}

	/** Contact address shown in the legal pages: admin@<domain>, filterable. */
	public static function email() {
		return apply_filters( 'agent_catalog_site_email', 'admin@' . self::domain() );
	}

	public static function uses_analytics() {
		$settings = get_option( 'pc_settings', array() );
		$on       = '1' === get_option( self::ANALYTICS_OPTION, '1' ) // on until the owner unticks it
			|| ( is_array( $settings ) && ! empty( $settings['metrika_counter_id'] ) );
		return (bool) apply_filters( 'agent_catalog_uses_analytics', $on );
	}

	public static function register_setting() {
		register_setting(
			'general',
			self::ANALYTICS_OPTION,
			array(
				'type'              => 'string',
				'sanitize_callback' => static function ( $v ) {
					return $v ? '1' : '';
				},
				'default'           => '1',
			)
		);
		add_settings_field(
			self::ANALYTICS_OPTION,
			'Сервисы аналитики',
			static function () {
				printf(
					'<label><input type="checkbox" name="%1$s" value="1" %2$s> На сайте используются сервисы аналитики (счётчики, пиксели)</label><p class="description">Включает cookie-уведомление и раздел об аналитике в политике обработки персональных данных. При указанном счётчике Метрики в настройках partner-catalog включается автоматически.</p>',
					esc_attr( self::ANALYTICS_OPTION ),
					checked( '1', get_option( self::ANALYTICS_OPTION, '1' ), false )
				);
			},
			'general'
		);
	}

	/**
	 * Cookie notice, variant A of docs/mockups/cookie-banner: a card bottom-left.
	 * Printed hidden; the inline script reveals it unless it was dismissed
	 * before, so returning visitors never see it flash. Notice mode only —
	 * analytics loads regardless, the card just informs and links the policy.
	 */
	public static function cookie_notice() {
		if ( is_admin() || ! self::uses_analytics() ) {
			return;
		}
		$policy = self::page_url( 'privacy' );
		if ( ! $policy ) {
			return;
		}
		?>
<div class="ac-cookie" id="ac-cookie" role="region" aria-label="Уведомление о cookies" hidden>
	<div class="ac-cookie-head"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9 4 4 0 0 1-5-5 4 4 0 0 1-4-4z"/><path d="M8.5 12h.01M12 16h.01M15.5 13h.01M9 8h.01"/></svg>Мы используем cookies</div>
	<p>Сайт использует cookies и сервисы аналитики сайта для анализа посещаемости. Подробнее — в <a href="<?php echo esc_url( $policy ); ?>">Политике обработки персональных данных</a>.</p>
	<button class="ac-cookie-btn" type="button">Понятно</button>
</div>
		<?php
		$key = wp_json_encode( self::NOTICE_KEY );
		wp_print_inline_script_tag(
			"(function(){var k={$key},el=document.getElementById('ac-cookie');if(!el)return;var seen=null;try{seen=localStorage.getItem(k);}catch(e){}if(seen)return;el.hidden=false;el.querySelector('button').addEventListener('click',function(){try{localStorage.setItem(k,'1');}catch(e){}el.hidden=true;});})();"
		);
	}

	/** Permalink of the theme-created page for $role, or '' if it is gone/unpublished. */
	public static function page_url( $role ) {
		$ids = get_posts(
			array(
				'post_type'        => 'page',
				'post_status'      => 'publish',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'meta_key'         => Agent_Catalog_Provision::MARKER,
				'meta_value'       => $role,
				'suppress_filters' => false,
			)
		);
		return $ids ? get_permalink( $ids[0] ) : '';
	}

	/** Replace {placeholders} in the body of the theme's legal pages only. */
	public static function fill_placeholders( $content ) {
		$id = get_the_ID();
		if ( ! $id || ! in_array( get_post_meta( $id, Agent_Catalog_Provision::MARKER, true ), self::ROLES, true ) ) {
			return $content;
		}
		$email = self::email();
		$map   = array(
			'{site_name}'   => esc_html( get_bloginfo( 'name' ) ),
			'{site_url}'    => esc_html( home_url( '/' ) ),
			'{site_domain}' => esc_html( self::domain() ),
			'{site_email}'  => '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>',
			'{policy_date}' => esc_html( get_the_modified_date( 'd.m.Y', $id ) ),
			'{privacy_url}' => esc_url( self::page_url( 'privacy' ) ),
			'{terms_url}'   => esc_url( self::page_url( 'terms' ) ),
		);
		return strtr( $content, $map );
	}

	/** Drop `ac-if-analytics` blocks when the site runs no analytics. */
	public static function filter_conditional( $html, $block ) {
		$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		if ( $class && preg_match( '/(^|\s)ac-if-analytics(\s|$)/', $class ) && ! self::uses_analytics() ) {
			return '';
		}
		return $html;
	}
}
