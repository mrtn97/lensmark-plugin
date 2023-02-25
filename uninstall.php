<?php
/**
 * Deactivation hook.
 */
function lnsmrk_deactivate() {
	// Unregister the post type, so the rules are no longer in memory.
	unregister_post_type( 'photopost' );
	// Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'lnsmrk_deactivate' );

?>