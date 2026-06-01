# OneClick Chat to Order

[![Active Installs](https://img.shields.io/wordpress/plugin/installs/oneclick-whatsapp-order?label=Active%20Installs&logo=wordpress&color=blue)](https://wordpress.org/plugins/oneclick-whatsapp-order/)
[![WordPress Rating](https://img.shields.io/wordpress/plugin/rating/oneclick-whatsapp-order?label=Rating&logo=wordpress)](https://wordpress.org/plugins/oneclick-whatsapp-order/)
[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/oneclick-whatsapp-order?label=Version&logo=wordpress)](https://wordpress.org/plugins/oneclick-whatsapp-order/)
[![License: GPLv3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

**Contributors:** walterpinem  
**Donate:** https://www.paypal.me/WalterPinem  
**License:** GPLv3 or later  
**Requires:** WordPress 6.0+, PHP 7.4+, WooCommerce 8.2+  

Transform your WooCommerce store with seamless WhatsApp integration. Let customers order instantly via WhatsApp with enhanced features.

🔗 **WordPress Plugin Page:** https://wordpress.org/plugins/oneclick-whatsapp-order/

---

## 🚀 Description

### Speed up the purchase process. Increase your sales!

Formerly known as **OneClick WhatsApp Order**, this plugin lets customers order products directly via WhatsApp with a single click.

Connect your WooCommerce store to WhatsApp and make ordering ridiculously fast and frictionless.

---

## 🎥 Overview & Tutorial

Watch the full walkthrough:

https://www.youtube.com/embed/LuURM5vZyB8

👉 Try it live: https://walterpinem.me/projects/oneclick/

---

## 🔌 Premium Add-ons

- **OneClick WCFM Connector**  
  https://www.onlinestorekit.com/oneclick-wcfm-connector/

- **OneClick Dokan Connector**  
  https://www.onlinestorekit.com/oneclick-dokan-connector/

- **OneClick WCVendors Connector**  
  https://www.onlinestorekit.com/oneclick-wcvendors-connector/

- **OneClick Variations Grabber**  
  https://www.onlinestorekit.com/oneclick-variations-grabber/

More add-ons coming soon.

---

## ✨ Features

### 🆕 Major Features

- Force `wa.me` URL for consistent behavior
- JavaScript onClick event option for Ajax compatibility
- Enhanced button styling
- Full uninstall cleanup system
- WPML multilingual support
- Conditional CSS classes for better theme integration

---

### ⚙️ Core Features

- Unlimited WhatsApp numbers
- Flexible button positioning (5 positions)
- Shortcode generator
- Advanced display control
- Button styling customization

---

### 📄 Page-Specific Features

- Single product customization
- Shop page integration
- Cart page WhatsApp checkout
- Thank you page override
- Floating WhatsApp button

---

### 🧠 Advanced Functionality

- Device-specific visibility
- Message customization
- Admin WhatsApp links in orders
- Source URL tracking
- Product variations support
- Dynamic shortcode system

---

### 🔐 Security & Compliance

- GDPR-ready with consent checkbox
- Input sanitization & XSS protection
- Optimized performance
- Accessibility compliant

---

## 📚 Documentation

- General Docs: https://www.onlinestorekit.com/docs/octo/
- Developer Docs: https://www.onlinestorekit.com/docs/octo/developer-documentation/

---

## 💬 Support

Get help or request features:

- https://walterpinem.me/projects/contact/
- https://www.onlinestorekit.com/support/

---

## 📦 Installation

1. Install and activate WooCommerce
2. Upload and activate this plugin
3. Go to **Chat to Order** menu
4. Configure settings
5. Done

---

## ❓ FAQ

### Is it free?
Yes. Completely free, unlimited usage.

### Can I use multiple WhatsApp numbers?
Yes. Assign numbers to:
- Products
- Pages
- Categories
- Departments

### What is Force wa.me option?
Forces all WhatsApp links to use:

```
https://wa.me/
```

Ensures consistent behavior across devices.

### Does it work with modern themes?
Yes. Includes compatibility for:
- Ajax Add to Cart
- Block themes
- Page builders

### Is it GDPR compliant?
Yes. Includes:
- Consent checkbox
- Privacy policy integration
- Data transparency

### Can I control button placement?
Yes. Includes:
- Product pages
- Shop loop
- Cart
- Thank you page
- Floating button
- Shortcodes

### Mobile support?
Fully responsive with:
- Touch-friendly UI
- Device-specific controls

### Multilingual support?
Yes:
- WPML compatible
- Translation-ready
- RTL support

---

## 🖼 Screenshots

1. Product settings panel  
2. Add WhatsApp number UI  
3. Product page button  
4. GDPR checkbox example  
5. Shop loop button  
6. Cart page button  
7. Thank you page button  
8. Shortcode output  

---

## 📝 Changelog

### 1.1.2 – May 27, 2026

#### Bug Fixes
- Fixed "Open in New Tab?" checkbox not saving on the Basic, Shop, Checkout, and Floating tabs. The four affected options (`wa_order_option_target`, `wa_order_option_shop_loop_open_new_tab`, `wa_order_option_custom_thank_you_open_new_tab`, `wa_order_floating_target`) were incorrectly registered with `sanitize_checkbox`, which only accepts `'yes'` and silently discards `'_blank'`. Changed all four to `sanitize_text_field` to match the Cart tab, which was already working correctly.

---

### 1.1.1 – March 27, 2026

#### New
- Automated checkbox migration routine
- Migration admin notice
- WooCommerce Block Cart support
- Block Cart checkout button control

#### Fixes
- Checkbox settings not saving
- Missing sanitization callbacks
- Cart button not showing in block themes

---

### 1.1.0 – Dec 11, 2025

#### Security Fix (CVE-2025-14270)
- Authorization checks added
- Admin-only settings control
- CSRF protection improvements

---

### 1.0.9 – Nov 07, 2025

#### Security Fix
- IDOR vulnerability resolved
- Order access validation added

---

### 1.0.8 – Aug 06, 2025

#### Major Update
- wa.me option
- onClick events
- WPML support
- Performance + security improvements

---

_(Older changelog entries preserved but omitted for brevity.)_

---

## 🔄 Upgrade Notice

### 1.1.2
- Fixes "Open in New Tab?" checkbox not saving on Basic, Shop, Checkout, and Floating tabs. Update immediately if you rely on opening WhatsApp links in a new tab from any of those pages.

### 1.1.1
- Adds migration system
- Fixes critical checkbox issue
- Improves Block Cart compatibility

---

Coded with ❤️ and ☕ by [Walter Pinem](https://walterpinem.com/).