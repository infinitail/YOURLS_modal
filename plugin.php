<?php
/*
Plugin Name: Modal
Plugin URI: https://github.com/infinitail/YOURLS_modal
Description: Replace Javascript confirm() on delete short URL to HTML modal.
Version: 0.1
Author: Infinitail
Author URI: https://github.com/infinitail
*/

// No direct call
if( !defined('YOURLS_ABSPATH') ) die();

// Add hook
//yourls_add_action( 'admin_menu', 'infinitail_modal' );
yourls_add_action( 'admin_page_before_content', 'infinitail_modal' );

// Rewrite onclick="remove_link();"
yourls_add_filter( 'table_add_row_action_array', 'infinitail_rewrite_remove_link' );

function infinitail_modal() {
    $plugin_url = yourls_plugin_url(__DIR__);
    $confirm_title   = yourls__( 'Delete confirm' );
    $confirm_message = yourls__( 'Really delete?' );
    $short_url = yourls__( 'Short URL' );
    $url       = yourls__( 'URL' );
    $delete    = yourls__( 'Delete' );
    $cancel    = yourls__( 'Cancel' );

    echo <<< __HTML__
    <link rel="stylesheet" href="{$plugin_url}/modal.css">
    <script src="{$plugin_url}/modal.js"></script>
    <div id="infinitail-delete-confirm-modal-dimmer"></div>
    <div id="infinitail-delete-confirm-modal">
        <h1>{$confirm_title}</h1>
        <div class="confirm-message">
            <p>{$confirm_message}</p>
            <p>
                {$short_url}: <span name="short_url"></span><br>
                {$url}: <span name="url"></span><br>
            </p>
        </div>
        <div class="button-group">
            <input type="button" class="button primary" value="{$delete}" onclick="infinitail_remove_link_confirmed();">
            <input type="reset" class="button" value="{$cancel}" onclick="infinitail_remove_link_cancel(); return false;">
            <input type="hidden" name="keyword_id" value="">
        </div>
    </div>
__HTML__;
}

/**
 *
 */
function infinitail_rewrite_remove_link(array $actions, string $keyword) {
    $rewrite = str_replace('remove_link(', 'infinitail_remove_link_modal(', $actions['delete']['onclick']);
    $actions['delete']['onclick'] = $rewrite;

    return $actions;
}