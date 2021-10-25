<?php

/**
 * Provide a admin area view for the plugin
 *
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Ultimate_Member_Discord_Add_On
 * @subpackage Ultimate_Member_Discord_Add_On/admin/partials
 */
?>
<h1><?php echo __( 'Ultimate member Discord Add On Settings', 'ultimate-member-discord-add-on' ); ?></h1>
<div id="ultimate-discord-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 1 }'>
  <ul class="skltbs-tab-group">
  <li class="skltbs-tab-item">
		<button class="skltbs-tab" data-identity="settings" ><?php echo __( 'Application details', 'ultimate-member-discord-add-on' ); ?><span class="initialtab spinner"></span></button>
  </li>
  <li class="skltbs-tab-item">
      <?php if ( ultimatemember_discord_check_saved_settings_status() ): ?>
      <button class="skltbs-tab" data-identity="level-mapping" ><?php echo __( 'Role Mappings', 'ultimate-member-discord-add-on' ); ?></button>
      <?php endif; ?>
  </li>
  </ul>
  <div class="skltbs-panel-group">
		<div id="ets_setting" class="ultimate-discord-tab-conetent skltbs-panel">
		<?php
			require_once ULTIMATE_MEMBER_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/ulimate_member_discord_application_details.php';
    ?>
		</div>
		<div id="ets_ultimatemember_discord_role_mapping" class="ultimate-discord-tab-conetent skltbs-panel">
		<?php
			require_once ULTIMATE_MEMBER_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/ulimate_member_discord_role_mapping.php';
    ?>
		</div>      
  </div>  
    
    <?php
//      $all_meta_for_user = get_user_meta( get_current_user_id() );
//      echo '<pre>';
//  print_r( $all_meta_for_user );
//  echo '</pre>';
    ?>
</div>
