<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <?php
     
    $stats = self::showStats();
    if ($stats) {
    	foreach (array_keys($stats) as $stat)
    	{
	    	?><p><?=$stat?>:&nbsp;<?=$stats[$stat]?></p><?php 
   		}
    } else {
    	?><p>.....</p><?php
    }
    ?>
    
</div>