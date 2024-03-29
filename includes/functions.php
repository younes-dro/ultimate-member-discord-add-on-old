<?php
/*
Common functions
*/

// function to get formated redirect url
function ets_get_ultimatemember_discord_formated_discord_redirect_url( $url ) {
	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=ultimate-discord';
	} else {
		if ( stristr( $url, 'via=ultimate-discord' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=ultimate-discord';
		}
	}
}

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function ultimatemember_discord_check_saved_settings_status() {
	$ets_ultimatemember_discord_client_id     = get_option( 'ets_ultimatemember_discord_client_id' );
	$ets_ultimatemember_discord_client_secret = get_option( 'ets_ultimatemember_discord_client_secret' );
	$ets_ultimatemember_discord_bot_token     = get_option( 'ets_ultimatemember_discord_bot_token' );
	$ets_ultimatemember_discord_redirect_url  = get_option( 'ets_ultimatemember_discord_redirect_url' );
	$ets_ultimatemember_discord_server_id      = get_option( 'ets_ultimatemember_discord_server_id' );

	if ( $ets_ultimatemember_discord_client_id && $ets_ultimatemember_discord_client_secret && $ets_ultimatemember_discord_bot_token && $ets_ultimatemember_discord_redirect_url && $ets_ultimatemember_discord_server_id ) {
			$status = true;
	} else {
			 $status = false;
	}

		 return $status;
}

  /**
   * Log API call response
   *
   * @param INT          $user_id
   * @param STRING       $api_url
   * @param ARRAY        $api_args
   * @param ARRAY|OBJECT $api_response
   */
function ets_ultimatemember_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = get_option( 'ets_ultimatemember_discord_log_api_response' );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		$logs = new Ultimate_Member_Discord_Add_On_Logs();
		$logs->write_api_response_logs( $log_string, $user_id );
	}
}

/**
 * Get  current Role Level id
 *
 * @param INT $user_id
 * @return INT|NULL $curr_level_id
 */
function ets_ultimatemember_discord_get_current_level_id( $user_id ) {
    um_fetch_user( $user_id );
    $curr_level_id = substr( UM()->user()->get_role(), 3 ) ;
    if ( $curr_level_id ) {
        $curr_level_id = sanitize_text_field( trim( $curr_level_id ) );
        return $curr_level_id;
       
    } else {
        return null;
	}
}

/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response
 * @param BOOLEAN
 */
function ets_ultimatemember_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], ETS_ULTIMATE_MEMBER_DISCORD_DONOT_RETRY_THESE_API_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, ETS_ULTIMATE_MEMBER_DISCORD_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
	}
}

/**
 * Get formatted message to send in DM
 *
 * @param INT $user_id
 * Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBER_ROLE], [SITE_URL], [BLOG_NAME]</small>
 */
function ets_ultimatemember_discord_get_formatted_dm( $user_id, $um_role_id, $message ) {
    
        $user_obj= get_user_by( 'id', $user_id );
	$MEMBER_USERNAME = $user_obj->user_login;
	$MEMBER_EMAIL    = $user_obj->user_email;
        
        $MEMBER_ROLE = UM()->roles()->get_roles()['um_'.$um_role_id] ;
        
	$SITE_URL  = get_bloginfo( 'url' );
	$BLOG_NAME = get_bloginfo( 'name' );        
    
    
        $find    = array(
		'[MEMBER_USERNAME]',
		'[MEMBER_EMAIL]',
		'[MEMBER_ROLE]',
		'[SITE_URL]',
		'[BLOG_NAME]',
	);
	$replace = array(
		$MEMBER_USERNAME,
		$MEMBER_EMAIL,
		$MEMBER_ROLE,
		$SITE_URL,
		$BLOG_NAME,
	);
        

	return str_replace( $find, $replace, $message );

}

/**
 * Get the highest available last attempt schedule time
 */

function ets_ultimatemember_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', ETS_UM_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 */
function ets_ultimatemember_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}
