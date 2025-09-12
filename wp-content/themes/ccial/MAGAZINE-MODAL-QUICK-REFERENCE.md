# Magazine Modal Quick Reference

## 🚀 Quick Setup Checklist

- [ ] Upload magazine cover to Media Library
- [ ] Add Calameo embed code to image Description field
- [ ] Create Avada modal named "magazine"
- [ ] Add image element with shortcode in Link field
- [ ] Test the functionality

## 📝 Shortcode Usage

### Basic Shortcode

```bash
[modalview id="123"]
```

Replace `123` with the actual attachment ID from Media Library.

### Auto-Detection Shortcode

```bash
[modalview]
```

Automatically detects attachment ID from the image.

## 🔗 Where to Use Shortcodes

1. **Avada Image Link Field** (Recommended)
   - Add Image element in Avada Builder
   - Set Link field to: `[modalview id="123"]`

2. **Page/Post Content**
   - Add directly in content editor
   - Works in any content area

## 📋 Calameo Embed Code Template

```html
<div style="text-align:center;">
    <iframe src="//v.calameo.com/?bkcode=YOUR_BOOK_CODE&mode=viewer&view=book&showsharemenu=false" 
            width="100%" height="100%" frameborder="0" scrolling="no" 
            allowtransparency allowfullscreen style="margin:0 auto;">
    </iframe>
    <div style="margin:4px 0px 8px;">
        <a href="http://www.calameo.com/" target="_blank">Read more publications on Calaméo</a>
    </div>
</div>
```

**Important:** Replace `YOUR_BOOK_CODE` with your actual Calameo book code.

## 🎯 Modal Setup

1. **Create Modal in Avada Builder**
   - Add Modal element
   - Set name to exactly: `magazine`
   - Configure size: Large or Extra Large

2. **Modal Content**
   - Content is automatically populated
   - No additional setup required

## 🐛 Troubleshooting

### Modal Won't Open

- ✅ Check modal name is exactly "magazine"
- ✅ Verify shortcode syntax: `[modalview id="123"]`
- ✅ Check browser console for errors

### Empty Modal

- ✅ Verify Calameo embed code in image Description
- ✅ Check attachment ID exists in Media Library
- ✅ Ensure embed code is complete and valid

### Wrong Title

- ✅ Expected format: "Revista Hoguera vol: filename"
- ✅ Check image filename in Media Library
- ✅ Verify translation settings if needed

## 📱 Mobile Testing

- ✅ Test on different screen sizes
- ✅ Verify touch interactions work
- ✅ Check modal responsiveness

## 🌍 Translation

To translate the title prefix:

1. Open `wp-content/themes/ccial/languages/ccial.pot`
2. Find: `msgid "Revista Hoguera vol: "`
3. Add: `msgstr "Your Translation Here: "`

**Examples:**

- English: `"Hoguera Magazine vol: "`
- French: `"Revue Hoguera vol: "`

## 📞 Support

### Before Contacting Support

1. ✅ Check this quick reference
2. ✅ Review the full Editor Guide
3. ✅ Test with a simple setup first
4. ✅ Check browser console for errors

### When Contacting Support

- Provide specific error messages
- Include browser and device information
- Describe exact steps that led to the issue

---

**Need more help?** See `MAGAZINE-MODAL-EDITOR-GUIDE.md` for detailed instructions.
