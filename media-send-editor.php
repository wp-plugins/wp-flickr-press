<?php
if ( isset($_POST['send']) && isset($_POST['attachments']) ) {
	$keys = array_keys($_POST['send']);
	$send_id = array_shift($keys);
	$attachments = array($send_id => $_POST['attachments'][$send_id]);
} else if ( isset($_POST['batch']) && $_POST['batch'] && isset($_POST['batch_send']) && count($_POST['batch_send'])>0 && isset($_POST['attachments']) ) {
	$attachments = array();
	foreach ($_POST['batch_send'] as $id) {
		if (isset($_POST['attachments'][$id])) {
			$attachments[$id] = $_POST['attachments'][$id];
		}
	}
} else {
	wp_die('does not exists key.');
}

//$photo = FlickrPress::getClient()->photos_getInfo($send_id);

media_send_to_editor(fp_create_image_html($attachments));

function fp_create_image_html($attachments) {

	$html = '';
	foreach ($attachments as $id => $attachment) {
		$photo = FlickrPress::getClient()->photos_getInfo($id);

	        $link = esc_url($attachment['url']);
	        $target = isset($attachment['target']) ? esc_attr($attachment['target']) : '';
	        $target = strlen($target)>0 ? " target='{$target}'" : '';
	        $align = isset($attachment['align']) ? esc_attr($attachment['align']) : '';
	        $src = isset($attachment['image-size']) ? esc_attr($attachment['image-size']) : '';
	        $alt = isset($attachment['title']) ? esc_attr($attachment['title']) : '';
	        if (strlen($src)==0) {
	                $src = FlickrPress::getPhotoUrl($photo);
	        }
	        if ( isset($attachment['align']) ) {
	                $align = esc_attr($attachment['align']);
	                $class = " class='align$align'";
	        }

		$_html = "<img src='{$src}' alt='{$alt}'{$class} />";
		if (strlen($link)>0) {
			$_html = "<a href='{$link}'{$target}>{$_html}</a>";
		}
		$html .= $_html;
	}

	return $html;
}
?>
