<?php
$args = array(
	'post_id' => get_the_ID(),
	'post_type' => 'product',
	'post_status' => 'publish',
	'number' => 5,
	'meta_query' => WC()->query->get_meta_query()
);

$comments_query = new WP_Comment_Query;
$comments = $comments_query->query($args);
if ($comments) :
?>

	<section class="reviews">

		<?php
		$the_user = get_user_by('id', get_current_user_id());
		foreach ($comments as $comment) :
			if ($comment->comment_approved == '1') :
		?>
				<div class="reviews__user-review js-user-review">
					<span class="reviews__user-review-name"><?= $comment->comment_author; ?>
					<?php if ($rating) : ?>
						<span>-</span>
						<?php if ($rating === '5') : ?>
							<svg class="thumbs-up">
								<use xlink:href="<?= get_template_directory_uri() ?>'/assets/img/icons/thumbs-up-icon.svg#thumbs-up" href="<?= get_template_directory_uri() ?>/assets/img/icons/thumbs-up-icon.svg#thumbs-up"></use>
							</svg>
						<?php endif; ?>

						<?php if ($rating === '1') : ?>
							<svg>
								<use xlink:href="<?= get_template_directory_uri() ?>/assets/img/icons/thumbs-down-icon.svg#thumbs-down" href="<?= get_template_directory_uri() ?>/assets/img/icons/thumbs-down-icon.svg#thumbs-down"></use>
							</svg>
						<?php endif; ?>

					<?php endif; ?>
				</span>
					<p class="reviews__user-review-comment"><?= $comment->comment_content; ?></p>
					<?php $rating = get_comment_meta($comment->comment_ID, 'rating', true); ?>
				

					<?php
					// Check if the current user is the one who wrote the review
					$current_user = wp_get_current_user();
					if ($current_user->ID == $comment->user_id) :
					?>
						<a href="#" class="reviews__user-review-remove js-delete-review" data-comment-id="<?= $comment->comment_ID; ?>" data-user-email="<?= $the_user->user_email; ?>"><?= __('Eemalda tagasiside', 'kewebshop'); ?></a>
					<?php endif; ?>

				</div>

		<? endif;
		endforeach; ?>

	</section>
<?
endif; ?>


<?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
	<div id="review_form_wrapper">
		<div id="review_form">
			<?php
			$commenter    = wp_get_current_commenter();
			$comment_form = array(
				/* translators: %s is product title */
				'title_reply'         => have_comments() ? '' : sprintf(esc_html__('Jäta tootele tagasiside', 'kewebshop'), get_the_title()),
				/* translators: %s is product title */
				'title_reply_to'      => esc_html__('Leave a Reply to %s', 'woocommerce'),
				'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
				'title_reply_after'   => '</span>',
				'comment_notes_after' => '',
				'label_submit'        => esc_html__('Submit', 'woocommerce'),
				'logged_in_as'        => '',
				'comment_field'       => '',
			);

			$name_email_required = (bool) get_option('require_name_email', 1);
			$fields              = array(
				'author' => array(
					'label'    => __('Name', 'woocommerce'),
					'type'     => 'text',
					'value'    => $commenter['comment_author'],
					'required' => $name_email_required,
				),
				'email'  => array(
					'label'    => __('Email', 'woocommerce'),
					'type'     => 'email',
					'value'    => $commenter['comment_author_email'],
					'required' => $name_email_required,
				),
			);

			$comment_form['fields'] = array();

			foreach ($fields as $key => $field) {
				$field_html  = '<p class="comment-form-' . esc_attr($key) . '">';
				$field_html .= '<label for="' . esc_attr($key) . '">' . esc_html($field['label']);

				if ($field['required']) {
					$field_html .= '&nbsp;<span class="required">*</span>';
				}

				$field_html .= '</label><input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' /></p>';

				$comment_form['fields'][$key] = $field_html;
			}

			$account_page_url = wc_get_page_permalink('myaccount');
			if ($account_page_url) {
				/* translators: %s opening and closing link tags respectively */
				$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf(esc_html__('You must be %1$slogged in%2$s to post a review.', 'woocommerce'), '<a href="' . esc_url($account_page_url) . '">', '</a>') . '</p>';
			}

			comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
			?>
		</div>
	</div>
<?php else : ?>
	<p class="woocommerce-verification-required"><?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>
<?php endif; ?>