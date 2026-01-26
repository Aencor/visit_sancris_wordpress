/**
 * Fix for ACF WYSIWYG editor loading blank in backend.
 * Triggers a resize event and re-initializes TinyMCE if needed.
 */
jQuery(document).ready(function ($) {
  // Function to refresh editors
  function refreshEditors() {
    $(window).trigger('resize');

    if (typeof tinymce !== 'undefined') {
      tinymce.editors.forEach(function (editor) {
        if (editor.hidden) {
          editor.show();
        }
        // Force repaint
        try {
          editor.execCommand('mceRepaint');
        } catch (e) { }
      });
    }
  }

  // Run on load
  setTimeout(refreshEditors, 500);
  setTimeout(refreshEditors, 1500);

  // Run when clicking tabs (common cause of blank editors)
  $('.acf-tab-button').on('click', function () {
    setTimeout(refreshEditors, 100);
  });
});
