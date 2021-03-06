<?php

/**
 * @file
 * Hooks provided by the Media WYSIWYG module.
 */

/**
 * Alter a list of view modes allowed for a file embedded in the WYSIWYG.
 *
 * @param array $view_modes
 *   An array of view modes that can be used on the file when embedded in the
 *   WYSIWYG.
 * @param object $file
 *   A file entity.
 *
 * @see media_get_wysiwyg_allowed_view_modes()
 */
function hook_media_wysiwyg_wysiwyg_allowed_view_modes_alter(&$view_modes, $file) {
  $view_modes['default']['label'] = t('Display an unmodified version of the file');
  unset($view_modes['preview']);
}

/**
 * Alter the WYSIWYG view mode selection form.
 *
 * Similar to a form_alter, but runs first so that modules can add
 * fields specific to a given file type (like alt tags on images) before alters
 * begin to work on the fields.
 *
 * @param array $form
 *   An associative array containing the structure of the form.
 * @param array $form_state
 *   An associative array containing the current state of the form.
 * @param object $file
 *   A file entity.
 *
 * @see media_format_form()
 */
function hook_media_wysiwyg_format_form_prepare_alter(&$form, &$form_state, $file) {
  $form['preview']['#access'] = FALSE;

  $file = $form_state['file'];
  $form['heading']['#markup'] = t('Embedding %filename of type %filetype', array('%filename' => $file->filename, '%filetype' => $file->type));
}

/**
 * Alter the output generated by Media filter tags.
 *
 * @param array $element
 *   The renderable array of output generated for the filter tag.
 * @param array $tag_info
 *   The filter tag converted into an associative array by
 *   media_token_to_markup() with the following elements:
 *   - 'fid': The ID of the media file being rendered.
 *   - 'file': The object from file_load() of the media file being rendered.
 *   - 'view_mode': The view mode being used to render the file.
 *   - 'attributes': An additional array of attributes that could be output
 *     with media_get_file_without_label().
 * @param array $settings
 *   An additional array of settings.
 *   - 'wysiwyg': A boolean if the output is for the WYSIWYG preview or FALSE
 *     if for normal rendering.
 *
 * @see media_token_to_markup()
 */
function hook_media_wysiwyg_token_to_markup_alter(&$element, $tag_info, $settings) {
  if (empty($settings['wysiwyg'])) {
    $element['#attributes']['alt'] = t('This media has been output using the @mode view mode.', array('@mode' => $tag_info['view_mode']));
  }
}
