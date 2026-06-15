<?php
/**
 * Force IPv4 for all WordPress HTTP requests.
 *
 * Docker bridge networks resolve DNS to IPv6 but have no IPv6 routing,
 * causing every external request to hang for the full timeout (5s+).
 * This hook sets CURLOPT_IPRESOLVE_V4 so curl always uses IPv4.
 */
add_action( 'http_api_curl', function ( $handle ) {
	curl_setopt( $handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
}, 10, 1 );
