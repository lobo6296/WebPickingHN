<?php 
//==============================================================================
//	SYSTEM CONSTANTS
//
//	Author: Max @ Toronto Emporium
//	E-mail: admin@torontoemporium.com
//
//	Description:
//	I built this module because I do all the coding for our website.
//	When I needed to dig into the system variables and constants, I could never
//	find a tool to show me what I neeeded.  That need inspired me to write this.
//
//	This has been tested using OC Version 1.5.6 - Default theme
//	NO WARRANTY is implied or expressed. Use at your own risk.
//
//	I am releasing this as FREEWARE covered under the GNU licensing model.
//	Use it or tailor it to your needs, but you MUST keep our credits!
//	Thats all we ask.  No donations required or requested.  LOL
//==============================================================================
// error_reporting(E_ALL); 
error_reporting(0); 
?>
<?php echo $header; ?>
<style>
.left1 {
	font-weight: 700;
	color: #090;
}
.right1 {
	font-weight: 700;
	color: #009;
}
.key1 {
	color: #d00;
	/* font-style: italic; */
}
</style>
<script>
$(document).ready(function(){
        $('ul.tabs').each(function(){
        // For each set of tabs, we want to keep track of
        // which tab is active and it's associated content
        var $active, $content, $links = $(this).find('a');

        // If the location.hash matches one of the links, use that as the active tab.
        // If no match is found, use the first link as the initial active tab.
        $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
        $active.addClass('active');
        $content = $($active.attr('href'));

        // Hide the remaining content
        $links.not($active).each(function () {
        $($(this).attr('href')).hide();
		});
</script>
<div id="content">
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="box">
    <div class="heading"><h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1></div>
	<div class="content">
		<div class="dashboard-content">
			<div id="latest_tabs" class="htabs">
				<a href="#tab-local-settings">Settings in DB</a>
				<a href="#tab-latest_constants">System Constants</a>
				<a href="#tab-latest_superglobals">SuperGlobals</a>
			</div>

			<div id="tab-local-settings" class="htabs-content">
				<?php
				include_once('local_settings.php');
				?><br><br><br><br>
			</div>
			
			<div id="tab-latest_constants" class="htabs-content">
				<?php
				include_once('var_print.php');
				$arr = get_defined_constants();
				print_r(var_log($arr,'<h3>DEFINED SYSTEM CONSTANTS</h3>'));
				?><br><br><br><br>
			</div>

			<div id="tab-latest_superglobals" class="htabs-content">  
				<?php
				include_once('sglobals.php');
				?><br><br><br><br>
			</div>
	</div>
</div>

<script type="text/javascript"><!--
        $('#latest_tabs a').tabs();
        //-->
</script>
<style type="text/css">
        <!--
        	.htabs {
        	padding: 0px 0px 0px 10px;
        	height: 30px;
        	line-height: 16px;
        	border-bottom: 1px solid #4080B0;
        	margin-bottom: 15px;
        }
        .htabs a {
        	border-top: 1px solid #4080B0;
        	border-left: 1px solid #4080B0;
        	border-right: 1px solid #4080B0;
        	background: #4080B0;
        	padding: 7px 15px 6px 15px;
        	float: left;
        	/* font-family: Arial, Helvetica, sans-serif;
        	font-size: 13px;
        	font-weight: 500; */
        	text-align: center;
        	text-decoration: none;
        	color: #f7f7f7;
        	margin-right: 2px;
        	border-radius: 3px 3px 0px 0px;
        	display: none;
        }
        .htabs a.selected {
        	padding-bottom: 7px;
			background: #F0F0F0;
        	color: #000000;
        }
        -->
</style>

<?php echo $footer; ?>
