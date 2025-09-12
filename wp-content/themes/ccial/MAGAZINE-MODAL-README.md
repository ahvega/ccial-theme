# Magazine Modal Functionality

This functionality implements a "click cover → open modal with embedded Calameo viewer" flow for magazine archive presentation on WordPress sites using the Avada theme.

## Overview

The system allows editors to:

1. Upload magazine cover images to the Media Library
2. Paste Calameo embed HTML into the image's Description field
3. Use a shortcode in Avada Image elements to make covers clickable
4. Display magazine content in a modal when covers are clicked

## Editor Workflow

### Step 1: Prepare Magazine Images

1. Upload magazine cover images to **Media Library**
2. For each cover image, click **Edit** to open the attachment details
3. In the **Description** field, paste the complete Calameo embed HTML code
4. Save the attachment

**Example Calameo embed HTML:**

```html
<iframe src="https://view.calameo.com/1234567890abcdef" width="100%" height="600" frameborder="0" allowfullscreen="true" allow="autoplay"></iframe>
```

### Step 2: Create Avada Modal

1. In Avada Builder, add a **Modal** element
2. Set the modal name to `magazine` (this is important!)
3. Configure the modal settings as needed
4. The modal content will be automatically populated when covers are clicked

**Note:** The modal title will automatically display "Revista Hoguera vol: " followed by the image filename (without file extension). This text is translatable through the theme's language files.

### Step 3: Add Clickable Covers

#### Option A: Using Shortcode in Link Field (Recommended)

1. Add an **Image** element in Avada Builder
2. Select your magazine cover image
3. In the **Link** field, enter: `[modalview id="ATTACHMENT_ID"]`
   - Replace `ATTACHMENT_ID` with the actual attachment ID from Media Library
4. Save the element

#### Option B: Auto-Detection in Link Field

1. Add an **Image** element in Avada Builder
2. Select your magazine cover image
3. In the **Link** field, enter: `[modalview]`
4. The system will automatically detect the attachment ID from the image
5. Save the element

#### Option C: Using Shortcode in Content (Alternative)

If the Link field doesn't process shortcodes:

1. Add an **Image** element in Avada Builder
2. Select your magazine cover image
3. In the **Caption** or **Description** field, enter: `[modalview id="ATTACHMENT_ID"]`
4. Save the element

**Note:** The shortcode outputs a special URL (`magazine-modal://123`) that JavaScript intercepts and converts to the proper Avada modal format.

## Finding Attachment IDs

### Method 1: Media Library

1. Go to **Media Library**
2. Click on an image to view details
3. The attachment ID is in the URL: `post.php?post=123&action=edit` (ID is 123)

### Method 2: Browser Developer Tools

1. Right-click on an image in Media Library
2. Select **Inspect Element**
3. Look for `data-attachment-id` attribute in the HTML

## Usage Examples

### Single Magazine Cover

```bash
Link: [modalview id="123"]
```

### Multiple Covers with Auto-Detection

```bash
Link: [modalview]
```

**Generated URL:** The shortcode will output:

```bash
magazine-modal://123
```

JavaScript then converts this to the proper Avada modal format:

```html
<a class="fusion-modal-text-link" data-toggle="modal" data-target=".fusion-modal.magazine" href="#">
```

## Features

- **Works in Carousels**: Covers work in Avada carousels and sliders
- **Multiple Covers**: Multiple covers on the same page all use the same modal
- **Auto-Detection**: Can automatically detect attachment IDs from images
- **Security**: All embed HTML is sanitized for security
- **Responsive**: Modal adapts to different screen sizes
- **Cleanup**: Modal content is cleared when closed to stop media playback

## Troubleshooting

### Modal Not Opening

- Ensure you created a modal element named `magazine` in Avada
- Check browser console for JavaScript errors
- Verify the shortcode syntax is correct

### No Embed Content

- Check that Calameo embed HTML is pasted in the image's Description field
- Verify the embed HTML is valid
- Ensure the attachment ID is correct

### Auto-Detection Not Working

- Use explicit attachment ID: `[modalview id="123"]`
- Check that the image has a `data-attachment-id` attribute
- Verify the image URL contains the attachment ID

## Technical Details

### Files Modified/Created

- `wp-content/themes/ccial/inc/magazine-modal.php` - Main functionality
- `wp-content/themes/ccial/js/magazine-modal.js` - Frontend JavaScript
- `wp-content/themes/ccial/functions.php` - Includes the modal functionality

### Security Features

- Nonce verification for AJAX requests
- HTML sanitization using `wp_kses()`
- User permission checks
- XSS protection in JavaScript

### Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Uses jQuery (included with WordPress)

## Translations

The modal title prefix "Revista Hoguera vol: " is translatable. To translate this text:

1. Open the theme's language file: `wp-content/themes/ccial/languages/ccial.pot`
2. Find the entry: `msgid "Revista Hoguera vol: "`
3. Add your translation in the `msgstr` field
4. Create language-specific `.po` and `.mo` files as needed

**Example for English:**

```bash
msgid "Revista Hoguera vol: "
msgstr "Hoguera Magazine vol: "
```

**Example title display:**

- **Image file:** `hoguera-2024-01.jpg`
- **Modal title:** `"Revista Hoguera vol: hoguera-2024-01"`

## Support

For issues or questions:

1. Check browser console for JavaScript errors
2. Verify all steps in the editor workflow
3. Test with a simple Calameo embed first
4. Contact the development team with specific error messages
