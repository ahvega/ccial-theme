# Magazine Modal Technical Documentation

This document provides technical details for developers working with the magazine modal functionality in the CCI AL theme.

## Architecture Overview

The magazine modal system consists of four main components:

1. **PHP Backend** (`inc/magazine-modal.php`) - Shortcode registration, AJAX endpoints, security
2. **JavaScript Frontend** (`js/magazine-modal.js`) - Event handling, modal interaction, AJAX calls
3. **CSS Styling** (`css/magazine-modal.css`) - Modal appearance, responsive design, dark theme
4. **Translation System** (`languages/ccial.pot`) - Internationalization support

## File Structure

```bash
wp-content/themes/ccial/
├── inc/
│   └── magazine-modal.php       # Core PHP functionality
├── js/
│   └── magazine-modal.js         # Frontend JavaScript
├── css/
│   └── magazine-modal.css        # Modal styling
├── languages/
│   └── ccial.pot                 # Translation template
├── functions.php                  # Includes magazine-modal.php
├── MAGAZINE-MODAL-README.md      # User documentation
├── MAGAZINE-MODAL-EDITOR-GUIDE.md # Editor instructions
└── MAGAZINE-MODAL-TECHNICAL.md   # This file
```

## PHP Backend (`inc/magazine-modal.php`)

### Class Structure

```php
class CCI_AL_Magazine_Modal {
    public function init()
    public function modalview_shortcode($atts)
    public function ajax_get_magazine_embed()
    public function enqueue_scripts()
    public function add_attachment_id_attribute($attr, $attachment, $size)
    public function process_link_shortcodes($link)
    public function process_content_shortcodes($content)
}
```

### Key Methods

#### `init()`

- Registers the `[modalview]` shortcode
- Sets up AJAX handlers for both logged-in and non-logged-in users
- Enqueues scripts and styles
- Adds attachment ID attributes to images
- Processes shortcodes in Avada Image Link fields

#### `modalview_shortcode($atts)`

- Processes shortcode attributes
- Supports `id` parameter for explicit attachment ID
- Returns custom URL scheme: `magazine-modal://{attachment_id}`
- Handles `auto` mode for automatic ID detection

#### `ajax_get_magazine_embed()`

- Validates nonce for security
- Retrieves attachment from database
- Checks user permissions
- Sanitizes embed HTML using `wp_kses()`
- Creates translatable title with filename
- Returns JSON response with embed HTML and formatted title

### Security Features

1. **Nonce Validation**

   ```php
   if (!wp_verify_nonce($_POST['nonce'], 'magazine_modal_nonce')) {
       wp_die('Security check failed');
   }
   ```

2. **Permission Checks**

   ```php
   if (!current_user_can('read_post', $attachment_id)) {
       wp_send_json_error('Access denied');
   }
   ```

3. **HTML Sanitization**

   ```php
   $allowed_html = array(
       'iframe' => array(
           'src' => array(), 'width' => array(), 'height' => array(),
           'frameborder' => array(), 'allow' => array(), 'allowfullscreen' => array(),
           'allowtransparency' => array(), 'scrolling' => array(), 'loading' => array(),
           'style' => array(), 'class' => array(), 'id' => array(), 'title' => array(),
           'sandbox' => array(), 'referrerpolicy' => array(),
       ),
       'div' => array('class' => array(), 'id' => array(), 'style' => array()),
       'a' => array('href' => array(), 'target' => array(), 'class' => array(), 'style' => array()),
       'script' => array('src' => array(), 'type' => array(), 'async' => array(), 'defer' => array()),
   );
   ```

## JavaScript Frontend (`js/magazine-modal.js`)

### Object Structure

```javascript
var CCI_ALMagazineModal = {
    currentAttachmentId: null,
    init: function(),
    bindEvents: function(),
    handleModalClick: function($link),
    resolveAttachmentId: function(href, $link),
    convertToModalLink: function($link),
    onModalShow: function($modal),
    onModalHide: function($modal),
    showMagazineEmbed: function($modal, embedHtml, title),
    makeEmbedResponsive: function(embedHtml),
    ensureModalExists: function(),
    showLoading: function($modal),
    showError: function($modal, message),
    clearModalContent: function($modal),
    escapeHtml: function(text)
};
```

### Event Handling

#### Click Interception

```javascript
$(document).on('click', 'a[href^="magazine-modal://"]', function(e) {
    e.preventDefault();
    self.handleModalClick($(this));
});
```

#### Modal Events

```javascript
$(document).on('show.bs.modal', '.fusion-modal.magazine', function() {
    self.onModalShow($(this));
});

$(document).on('hide.bs.modal', '.fusion-modal.magazine', function() {
    self.onModalHide($(this));
});
```

### AJAX Implementation

```javascript
$.ajax({
    url: ccialMagazineModal.ajaxUrl,
    type: 'POST',
    data: {
        action: 'get_magazine_embed',
        attachment_id: attachmentId,
        nonce: ccialMagazineModal.nonce
    },
    success: function(response) {
        if (response.success) {
            self.showMagazineEmbed($modal, response.data.embed_html, response.data.formatted_title);
        } else {
            self.showError($modal, response.data || 'Failed to load magazine embed.');
        }
    }
});
```

## CSS Styling (`css/magazine-modal.css`)

### Modal Structure

```css
.fusion-modal.magazine .modal-dialog {
    max-width: 90vw;
    width: 1200px;
}

.fusion-modal.magazine .modal-body {
    padding: 0;
    background: #2d2d2d;
    height: 85vh;
    overflow: hidden;
    position: relative;
    z-index: 1;
}
```

### Dark Theme Implementation

```css
.fusion-modal.magazine .modal-content {
    background-color: #2d2d2d !important;
    border: 1px solid #404040 !important;
}

.fusion-modal.magazine .modal-header {
    background-color: #1a1a1a !important;
    border-bottom: 1px solid #404040 !important;
    color: #ffffff !important;
}
```

### Responsive Design

```css
@media (max-width: 768px) {
    .fusion-modal.magazine .modal-dialog {
        max-width: 95vw;
        margin: 10px;
    }
    .fusion-modal.magazine .modal-body {
        height: 80vh;
    }
}

@media (max-width: 480px) {
    .fusion-modal.magazine .modal-dialog {
        max-width: 100vw;
        margin: 5px;
    }
    .fusion-modal.magazine .modal-body {
        height: 75vh;
    }
}
```

### Calameo Integration

```css
.fusion-modal.magazine #magazine-modal-target>div {
    height: 100% !important;
    width: 100% !important;
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    pointer-events: none !important;
}

.fusion-modal.magazine #magazine-modal-target iframe {
    width: 100% !important;
    height: 100% !important;
    border: none !important;
    display: block !important;
    pointer-events: auto !important;
    z-index: 1 !important;
    position: relative !important;
}
```

## Translation System

### Translation File Structure

```pot
#: inc/magazine-modal.php:159
msgid "Revista Hoguera vol: "
msgstr ""
```

### Implementation

```php
$title_prefix = __('Revista Hoguera vol: ', 'ccial');
$filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
$formatted_title = $title_prefix . $filename_without_ext;
```

## Integration Points

### WordPress Hooks

1. **Shortcode Registration**

   ```php
   add_shortcode('modalview', array($this, 'modalview_shortcode'));
   ```

2. **AJAX Handlers**

   ```php
   add_action('wp_ajax_get_magazine_embed', array($this, 'ajax_get_magazine_embed'));
   add_action('wp_ajax_nopriv_get_magazine_embed', array($this, 'ajax_get_magazine_embed'));
   ```

3. **Image Attributes**

   ```php
   add_filter('wp_get_attachment_image_attributes', array($this, 'add_attachment_id_attribute'), 10, 3);
   ```

4. **Avada Integration**

   ```php
   add_filter('fusion_attr_imageframe-link', array($this, 'process_link_shortcodes'));
   add_filter('the_content', array($this, 'process_content_shortcodes'));
   ```

### Avada Theme Integration

The system integrates with Avada's Bootstrap-based modal system:

1. **Modal Detection**
   - Looks for `.fusion-modal.magazine` elements
   - Uses Avada's native modal events (`show.bs.modal`, `hide.bs.modal`)

2. **Link Processing**
   - Processes shortcodes in Avada Image Link fields
   - Converts custom URL scheme to Avada-compatible modal triggers

## Security Considerations

### Input Validation

1. **Attachment ID Validation**

   ```php
   $attachment_id = intval($_POST['attachment_id']);
   if ($attachment_id <= 0) {
       wp_send_json_error('Invalid attachment ID');
   }
   ```

2. **HTML Sanitization**
   - Uses `wp_kses()` with strict allowlist
   - Prevents XSS attacks
   - Allows only necessary iframe attributes

3. **Permission Checks**
   - Verifies user can read the attachment
   - Prevents unauthorized access to private content

### JavaScript Security

1. **Nonce Validation**
   - All AJAX requests include nonce
   - Server validates nonce before processing

2. **HTML Escaping**

   ```javascript
   escapeHtml: function(text) {
       var map = {
           '&': '&amp;',
           '<': '&lt;',
           '>': '&gt;',
           '"': '&quot;',
           "'": '&#039;'
       };
       return text.replace(/[&<>"']/g, function(m) { return map[m]; });
   }
   ```

## Performance Optimizations

### Script Loading

1. **Conditional Enqueuing**

   ```php
   if (is_admin() || wp_doing_ajax()) {
       return;
   }
   ```

2. **Localized Data**

   ```php
   wp_localize_script('ccial-magazine-modal', 'ccialMagazineModal', array(
       'ajaxUrl' => admin_url('admin-ajax.php'),
       'nonce' => wp_create_nonce('magazine_modal_nonce'),
       'modalSelector' => '.fusion-modal.magazine',
       'modalBodySelector' => '.fusion-modal.magazine .modal-body',
       'modalTargetSelector' => '#magazine-modal-target'
   ));
   ```

### CSS Optimization

1. **Specific Selectors**
   - Uses specific selectors to avoid conflicts
   - Minimizes CSS specificity issues

2. **Responsive Design**
   - Uses viewport units (`vh`, `vw`)
   - Optimized for different screen sizes

## Browser Compatibility

### Supported Browsers

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

### Feature Detection

The system gracefully degrades if JavaScript is disabled:

1. **No JavaScript**
   - Shortcodes render as regular links
   - No modal functionality
   - Fallback to standard link behavior

2. **AJAX Failures**
   - Shows error messages
   - Provides fallback options
   - Logs errors for debugging

## Debugging

### JavaScript Console

Enable debugging by uncommenting console.log statements:

```javascript
// Debug: console.log('AJAX Response:', response);
// Debug: console.log('Original embed HTML:', embedHtml);
// Debug: console.log('Processed embed HTML:', processedHtml);
```

### PHP Debugging

Add error logging:

```php
error_log('Magazine Modal Debug: ' . print_r($data, true));
```

### Common Issues

1. **Modal Not Opening**
   - Check modal name is exactly "magazine"
   - Verify JavaScript is loading
   - Check for console errors

2. **Empty Modal**
   - Verify embed code in image description
   - Check attachment ID validity
   - Verify AJAX response

3. **Styling Issues**
   - Check CSS specificity
   - Verify Avada theme compatibility
   - Test responsive breakpoints

## Future Enhancements

### Potential Improvements

1. **Caching**
   - Cache embed HTML for better performance
   - Implement cache invalidation

2. **Analytics**
   - Track modal opens and closes
   - Monitor user engagement

3. **Accessibility**
   - Add ARIA labels
   - Keyboard navigation support
   - Screen reader compatibility

4. **Performance**
   - Lazy loading of modal content
   - Preloading of popular magazines

---

**Last Updated:** February 2025  
**Version:** 1.1.0  
**Compatible with:** WordPress 5.0+, Avada Theme, Calameo Embed Service
