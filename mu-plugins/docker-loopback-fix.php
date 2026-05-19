<?php
/**
 * Fix WordPress loopback requests in Docker.
 *
 * Inside the container Apache runs on port 80, but the siteurl uses
 * port 8080 (the Docker host mapping). This intercepts loopback requests
 * and rewrites them to the internal port before they go out.
 */
function docker_loopback_fix( $preempt, $args, $url ) {
	if ( strpos( $url, 'localhost:8080' ) === false ) {
		return $preempt;
	}

	$internal_url = str_replace( 'localhost:8080', 'localhost:80', $url );

	remove_filter( 'pre_http_request', 'docker_loopback_fix', 10 );
	$response = wp_remote_request( $internal_url, $args );
	add_filter( 'pre_http_request', 'docker_loopback_fix', 10, 3 );

	return $response;
}
add_filter( 'pre_http_request', 'docker_loopback_fix', 10, 3 );
