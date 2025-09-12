# Magazine Modal Editor Guide

This guide provides step-by-step instructions for editors to set up and use the magazine modal functionality on the CCIAL website.

## Overview

The magazine modal system allows visitors to click on magazine cover images and view the full publication in a professional, full-screen modal window. This system integrates with Calameo's embed service to provide an interactive reading experience.

## Quick Start Checklist

- [ ] Upload magazine cover images to Media Library
- [ ] Add Calameo embed code to image descriptions
- [ ] Create Avada modal named "magazine"
- [ ] Add clickable image elements with shortcodes
- [ ] Test the functionality

## Step-by-Step Setup

### Step 1: Prepare Magazine Images

1. **Upload Cover Images**
   - Go to **Media Library** in WordPress admin
   - Upload your magazine cover images (JPG, PNG, or WebP)
   - Note the **Attachment ID** for each image (visible in the URL when editing)

2. **Add Calameo Embed Code**
   - Click **Edit** on each magazine cover image
   - In the **Description** field, paste the complete Calameo embed HTML
   - **Save** the attachment

   **Example Calameo embed code:**
   ```html
   <div style="text-align:center;">
       <iframe src="//v.calameo.com/?bkcode=0080347544d6ff6e4266d&mode=viewer&view=book&showsharemenu=false" 
               width="100%" height="100%" frameborder="0" scrolling="no" 
               allowtransparency allowfullscreen style="margin:0 auto;">
       </iframe>
       <div style="margin:4px 0px 8px;">
           <a href="http://www.calameo.com/" target="_blank">Read more publications on Calaméo</a>
       </div>
   </div>
   ```

   **Important:** Use Calameo's embed code with `width="100%" height="100%"` for best results.

### Step 2: Create the Magazine Modal

1. **Add Modal Element**
   - In Avada Builder, add a **Modal** element
   - Set the modal name to exactly `magazine` (this is crucial!)
   - Configure modal settings:
     - **Modal Size**: Large or Extra Large
     - **Modal Animation**: Fade
     - **Close Button**: Enabled
   - **Save** the modal

2. **Modal Content**
   - The modal content will be automatically populated when covers are clicked
   - No additional content setup is required

### Step 3: Add Clickable Magazine Covers

#### Option A: Using Shortcode with Specific ID (Recommended)

1. **Add Image Element**
   - In Avada Builder, add an **Image** element
   - Select your magazine cover image
   - In the **Link** field, enter: `[modalview id="ATTACHMENT_ID"]`
   - Replace `ATTACHMENT_ID` with the actual attachment ID from Media Library
   - **Save** the element

   **Example:**
   ```
   [modalview id="1234"]
   ```

#### Option B: Using Auto-Detection Shortcode

1. **Add Image Element**
   - In Avada Builder, add an **Image** element
   - Select your magazine cover image
   - In the **Link** field, enter: `[modalview]`
   - The system will automatically detect the attachment ID from the image
   - **Save** the element

#### Option C: Using Shortcode in Content Areas

1. **In Page/Post Content**
   - Add the shortcode directly in the content editor
   - Use `[modalview id="ATTACHMENT_ID"]` or `[modalview]`
   - The shortcode will render as a clickable link

### Step 4: Testing

1. **Preview the Page**
   - View the page with your magazine covers
   - Click on a cover image
   - Verify the modal opens with the magazine content
   - Test navigation within the magazine viewer
   - Close the modal and test again

2. **Check Different Scenarios**
   - Test on desktop and mobile devices
   - Test with multiple covers on the same page
   - Test in Avada carousels (if applicable)

## Troubleshooting

### Modal Doesn't Open

**Possible Causes:**
- Modal name is not exactly "magazine"
- Shortcode syntax is incorrect
- JavaScript errors in browser console

**Solutions:**
- Verify modal name is "magazine" (case-sensitive)
- Check shortcode syntax: `[modalview id="123"]`
- Check browser console for JavaScript errors

### Empty Modal Content

**Possible Causes:**
- No Calameo embed code in image description
- Invalid attachment ID
- Calameo embed code is malformed

**Solutions:**
- Verify embed code is in the image's Description field
- Check that attachment ID exists in Media Library
- Ensure Calameo embed code is complete and valid

### Modal Title Issues

**Expected Title Format:**
- `"Revista Hoguera vol: filename-without-extension"`

**If Title is Wrong:**
- Check image filename in Media Library
- Verify translation settings if using non-Spanish language

### Mobile Issues

**Common Problems:**
- Modal too small on mobile
- Touch interactions not working

**Solutions:**
- Modal automatically adjusts for mobile
- Ensure touch events are enabled in browser

## Best Practices

### Image Preparation

1. **File Naming**
   - Use descriptive filenames: `hoguera-2024-01.jpg`
   - Avoid special characters and spaces
   - Keep filenames consistent

2. **Image Quality**
   - Use high-quality images for covers
   - Optimize file sizes for web
   - Use consistent dimensions

### Calameo Setup

1. **Embed Settings**
   - Use `width="100%" height="100%"`
   - Enable `allowfullscreen`
   - Set `frameborder="0"`
   - Use `mode=viewer&view=book` for best experience

2. **Publication Settings**
   - Ensure publication is publicly accessible
   - Test embed code in Calameo's preview
   - Verify all pages load correctly

### Content Organization

1. **Multiple Magazines**
   - Each magazine needs its own cover image
   - Each cover needs its own Calameo embed code
   - Use consistent naming conventions

2. **Page Layout**
   - Group related magazines together
   - Use Avada's grid or carousel elements
   - Maintain consistent spacing

## Advanced Usage

### Customizing Modal Title

The modal title format can be translated:

1. **Edit Translation File**
   - Open `wp-content/themes/ccial/languages/ccial.pot`
   - Find: `msgid "Revista Hoguera vol: "`
   - Add translation: `msgstr "Your Translation Here: "`

2. **Example Translations**
   - English: `"Hoguera Magazine vol: "`
   - French: `"Revue Hoguera vol: "`

### Using in Carousels

The magazine modal works seamlessly with Avada carousels:

1. **Add Carousel Element**
   - Create an Avada carousel
   - Add magazine cover images
   - Set each image's link to `[modalview id="ID"]`

2. **Benefits**
   - Multiple magazines in one carousel
   - Consistent modal experience
   - Mobile-friendly navigation

### Error Handling

The system includes built-in error handling:

1. **Loading States**
   - Shows spinner while loading
   - Professional loading animation

2. **Error Messages**
   - Friendly error messages for users
   - Detailed error logging for developers

3. **Fallback Behavior**
   - Graceful degradation if JavaScript fails
   - Clear instructions for troubleshooting

## Support

### Getting Help

1. **Check Documentation**
   - Review this guide thoroughly
   - Check the main README.md file
   - Review MAGAZINE-MODAL-README.md for technical details

2. **Common Issues**
   - Verify all steps in the setup process
   - Test with a simple Calameo embed first
   - Check browser console for errors

3. **Contact Support**
   - Provide specific error messages
   - Include browser and device information
   - Describe the exact steps that led to the issue

### Maintenance

1. **Regular Updates**
   - Keep WordPress and Avada updated
   - Monitor Calameo embed functionality
   - Test modal functionality after updates

2. **Performance Monitoring**
   - Monitor page load times
   - Check for JavaScript errors
   - Verify modal responsiveness

---

**Last Updated:** February 2025  
**Version:** 1.1.0  
**Compatible with:** WordPress 5.0+, Avada Theme, Calameo Embed Service
