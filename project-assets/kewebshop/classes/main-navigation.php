<?php
class kewebShop_menu extends Walker_Nav_Menu
{

	function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
	{
		if(get_field('highlight', $item)){
			$highlightClass = 'highlight--text';
		}else{
			$highlightClass = '';
		}
		$firstCondition = !$args->walker->has_children;
		$secondCondition = $depth == 0;
		$output .= "<li class='" .  implode(" ", $item->classes) . "'>";

		if ($firstCondition) {
			$output .= '<a class="'. $highlightClass .'" href="' . $item->url . '">';
		} else if ($secondCondition) {
			$output .= '<span class="js-main-item'. ' ' . $highlightClass .'">';
		} else {
			$output .= '<a href="' . $item->url . '" class="js-second-title">';
		}

		$output .= $item->title;

		if ($firstCondition) {
			$output .= '</a>';
		} 
		else if ($secondCondition) {
			$output .= '</span>';
		}
		else {
			$output .= '</a>';
		}
	}

	function start_lvl(&$output, $depth = 0, $args = null)
	{
		// Class change for different levels
		if ($depth == 0) {
			$output .= $indent . '<div class="second-level js-second-level"><ul class="second-level-menu">';
		} else if ($depth == 1) {
			$output .= $indent . '<ul class="third-level js-menu">';
		} else {
			$output .= $indent . '<ul class="fourth-level">';
		}
	}
}
