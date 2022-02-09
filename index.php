<?php
/**
 * @package axonplugin
 */
/*
Plugin Name: Imgen
Plugin URI: https://axoncodes.com/
Description: Auto image generator to WEBP and JPG
Version: 2.0
Author: AxoncCodes
Author URI: https://axoncodes.com/
Text Domain: axoncodes
*/



/**
 * Register a custom menu page.
 */
add_action( 'admin_menu', 'axoncodes_plugin' );
function axoncodes_plugin() {
	add_menu_page(
		'Image Generator',
		'Image Generator',
		'manage_options',
		'imgen/imgen-admin.php',
		'',
		plugins_url( 'imgen/icon.png' ),
		6
	);
}


add_action( 'add_attachment', 'image_upload_process_to_convert' );
function image_upload_process_to_convert( $attachment_id ) {
	$file = get_attached_file($attachment_id);
	$path = pathinfo($file);
	if($path['extension'] == "webp" || $path['extension'] == "jpg" || $path['extension'] == "jpeg" || $path['extension'] == "png") {
		$address = $path['dirname'];
		$filename = $path['filename'];
		$fileexe = $path['extension'];
		list($width, $height, $type, $attr) = getimagesize("$address/$filename.$fileexe");
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.axoncodes.com/imgen/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => "filename=$filename&address=$address&fileexe=$fileexe&width=$width",
		));
		$response = curl_exec($curl);
		curl_close($curl);
	}
}

require_once('delete.php');
add_action( 'delete_attachment', 'image_delete_process', 10, 2 );
function image_delete_process( $post_id, $post ){
	$file = get_attached_file($post_id);
	$path = pathinfo($file);
	if($path['extension'] == "webp" || $path['extension'] == "jpg" || $path['extension'] == "jpeg" || $path['extension'] == "png") {
		deleteFile($path['dirname'], $path['filename']);
	}
}

// disable srcset on frontend
function disable_wp_responsive_images(){return 1;}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');
add_filter( 'big_image_size_threshold', '__return_false' );

// custom image tag
function axgImgen($src, $alt, $id, $class, $loading, $width, $height, $sizes) {
	$useragentos = $_SERVER["HTTP_USER_AGENT"];
	$generalimgexe=".jpg";
	$imgmainsrc = $src;
	$baseimgsrc = substr($imgmainsrc, 0, strripos($imgmainsrc, '.'));
	$exeimgsrc = substr($imgmainsrc, strripos($imgmainsrc, '.'));
	$generalimgexe = $exeimgsrc;
	$newimgsrcset = $baseimgsrc.$exeimgsrc;
	$imgsrcsetqueue = "";
	foreach ($sizes as $size) {
		if ($size == "thumbnail") $src= "$baseimgsrc-$size$generalimgexe";
		elseif ($size == "small") $imgsrcsetqueue.= "$baseimgsrc-$size$generalimgexe 300w,";
		elseif ($size == "medium") $imgsrcsetqueue.= "$baseimgsrc-$size$generalimgexe 900w,";
		elseif ($size == "large") $imgsrcsetqueue.= "$baseimgsrc-$size$generalimgexe 1500w,";
	}
	echo "<img loading='$loading' id='$id' src='$src' alt='$alt' class='$class' srcset='$imgsrcsetqueue' />";
}