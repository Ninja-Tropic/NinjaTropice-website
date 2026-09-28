(function ($) {
	if (typeof acf === 'undefined' || !acf.addFilter) {
		return;
	}

	var TYPE_FIELD_KEY = 'field_ninjatheme_post_highlights_post_type';
	var POST_FIELD_KEY = 'field_ninjatheme_post_highlights_post';

	// Restrict the Post dropdown's search results to the type chosen in the
	// same repeater row, so editors pick a type first, then search within it.
	acf.addFilter('select2_ajax_data', function (data, fieldData, $el) {
		var $field = $el.closest('.acf-field[data-key="' + POST_FIELD_KEY + '"]');
		if (!$field.length) {
			return data;
		}

		var $row = $field.closest('.acf-row');
		var $typeSelect = $row.find('.acf-field[data-key="' + TYPE_FIELD_KEY + '"] select');

		if ($typeSelect.length) {
			data.post_type_filter = $typeSelect.val();
		}

		return data;
	});

	// Clear the selected post when the row's type changes, so a stale
	// selection from a different post type can't be left behind.
	$(document).on('change', '.acf-field[data-key="' + TYPE_FIELD_KEY + '"] select', function () {
		var $row = $(this).closest('.acf-row');
		var $postSelect = $row.find('.acf-field[data-key="' + POST_FIELD_KEY + '"] select');

		if ($postSelect.length && $postSelect.hasClass('select2-hidden-accessible')) {
			$postSelect.val(null).trigger('change');
		}
	});
})(jQuery);
