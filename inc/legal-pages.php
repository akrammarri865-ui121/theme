<?php
/**
 * Legal & Trust Pages - Auto-generation on theme activation
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create all legal/trust pages on theme activation.
 */
function stb_create_legal_pages() {
    $pages = stb_get_legal_pages_data();

    foreach ( $pages as $slug => $page_data ) {
        // Check if page already exists.
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            continue;
        }

        wp_insert_post( array(
            'post_title'   => $page_data['title'],
            'post_name'    => $slug,
            'post_content' => $page_data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
            'meta_input'   => array(
                '_stb_legal_page' => true,
            ),
        ) );
    }

    // Create legal footer menu.
    stb_create_legal_menu();
}
add_action( 'after_switch_theme', 'stb_create_legal_pages' );


/**
 * Create footer legal navigation menu automatically.
 */
function stb_create_legal_menu() {
    $menu_name = 'Legal Pages';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        return;
    }

    $menu_id = wp_create_nav_menu( $menu_name );

    if ( is_wp_error( $menu_id ) ) {
        return;
    }

    $legal_slugs = array(
        'about-us', 'contact-us', 'privacy-policy',
        'terms-and-conditions', 'disclaimer', 'cookie-policy',
        'affiliate-disclosure', 'dmca-policy', 'editorial-policy',
    );

    $order = 1;
    foreach ( $legal_slugs as $slug ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'     => get_the_title( $page ),
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $order++,
            ) );
        }
    }

    // Assign to footer-legal location.
    $locations = get_theme_mod( 'nav_menu_locations', array() );
    $locations['footer-legal'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}


/**
 * Register footer-legal menu location.
 */
function stb_register_legal_menu() {
    register_nav_menu( 'footer-legal', __( 'Footer Legal Menu', 'smarttoolsblog' ) );
}
add_action( 'after_setup_theme', 'stb_register_legal_menu', 20 );

/**
 * Get all legal pages data with real professional content.
 *
 * @return array Page slug => array( title, content ).
 */
function stb_get_legal_pages_data() {
    return array(
        'about-us'              => stb_page_about_us(),
        'contact-us'            => stb_page_contact_us(),
        'privacy-policy'        => stb_page_privacy_policy(),
        'terms-and-conditions'  => stb_page_terms_conditions(),
        'disclaimer'            => stb_page_disclaimer(),
        'cookie-policy'         => stb_page_cookie_policy(),
        'affiliate-disclosure'  => stb_page_affiliate_disclosure(),
        'dmca-policy'           => stb_page_dmca_policy(),
        'editorial-policy'      => stb_page_editorial_policy(),
    );
}


/**
 * About Us page content.
 */
function stb_page_about_us() {
    return array(
        'title'   => 'About Us',
        'content' => '<!-- wp:heading {"level":2} -->
<h2>Welcome to Factaxy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy is a modern digital platform dedicated to providing high-quality blogs, online tools, comprehensive guides, educational content, AI-powered tools, calculators, and technology-related resources. We believe in making complex information accessible to everyone through accuracy, simplicity, and user-friendly presentation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Our Mission</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our mission is to empower users with reliable information and practical tools that simplify their digital lives. Whether you are looking for in-depth technology guides, handy online calculators, AI tool reviews, or educational resources, Factaxy delivers content that is thoroughly researched, clearly written, and designed to provide real value.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>What We Offer</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Blogs &amp; Articles:</strong> In-depth articles covering technology, digital tools, and educational topics.</li>
<li><strong>Online Tools &amp; Calculators:</strong> Free, easy-to-use tools designed to solve everyday problems.</li>
<li><strong>AI Tool Reviews:</strong> Honest reviews and guides for the latest AI-powered solutions.</li>
<li><strong>Educational Guides:</strong> Step-by-step tutorials and how-to guides for learners at all levels.</li>
<li><strong>Technology Resources:</strong> Up-to-date coverage of emerging technologies and digital trends.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Our Values</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>At Factaxy, we are committed to accuracy in every piece of content we publish. Our editorial team follows strict fact-checking standards to ensure that the information we share is reliable and up to date. We prioritize user experience by maintaining clean, fast-loading pages with intuitive navigation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>About the Founder</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy was founded by <strong>Waseem Akram</strong>, a passionate technology enthusiast and content creator dedicated to building resources that genuinely help people navigate the digital world. With a focus on quality over quantity, Waseem leads the editorial direction to ensure every article, tool, and guide meets the highest standards.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Get in Touch</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Have questions, suggestions, or collaboration ideas? We would love to hear from you. Visit our <a href="/contact-us">Contact Us</a> page or email us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Contact Us page content.
 */
function stb_page_contact_us() {
    return array(
        'title'   => 'Contact Us',
        'content' => '<!-- wp:heading {"level":2} -->
<h2>Get in Touch</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We value your feedback, questions, and suggestions. Whether you want to report an issue, suggest a new tool, propose a collaboration, or simply say hello, we are here to help. Please use the information below to reach us.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Email Us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>General Inquiries:</strong> <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Support &amp; Feedback:</strong> <a href="mailto:akrammarri865@gmail.com">akrammarri865@gmail.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact Form</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Fill out the form below and we will get back to you within 24-48 hours.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<form class="stb-contact-form" method="post" action="#">
    <div class="form-group">
        <label for="contact-name">Your Name <span class="required">*</span></label>
        <input type="text" id="contact-name" name="name" required placeholder="Enter your full name">
    </div>
    <div class="form-group">
        <label for="contact-email">Email Address <span class="required">*</span></label>
        <input type="email" id="contact-email" name="email" required placeholder="Enter your email address">
    </div>
    <div class="form-group">
        <label for="contact-subject">Subject</label>
        <input type="text" id="contact-subject" name="subject" placeholder="What is this about?">
    </div>
    <div class="form-group">
        <label for="contact-message">Message <span class="required">*</span></label>
        <textarea id="contact-message" name="message" rows="6" required placeholder="Write your message here..."></textarea>
    </div>
    <button type="submit" class="btn btn--primary">Send Message</button>
</form>
<!-- /wp:html -->

<!-- wp:heading {"level":2} -->
<h2>Connect With Us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Follow Factaxy on social media for the latest updates, tool releases, and helpful content:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Twitter / X: @factaxy</li>
<li>Facebook: facebook.com/factaxy</li>
<li>YouTube: youtube.com/@factaxy</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Response Time</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We aim to respond to all inquiries within 24 to 48 business hours. For urgent matters, please include "URGENT" in your email subject line.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Privacy Policy page content.
 */
function stb_page_privacy_policy() {
    return array(
        'title'   => 'Privacy Policy',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>At Factaxy (factaxy.com), we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website. Please read this policy carefully.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Information We Collect</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may collect information about you in various ways, including:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><strong>Personal Data:</strong> Name and email address when you voluntarily submit them through contact forms or newsletter subscriptions.</li>
<li><strong>Usage Data:</strong> Information automatically collected when you visit our site, including IP address, browser type, operating system, referring URLs, pages viewed, and time spent on pages.</li>
<li><strong>Cookies and Tracking:</strong> We use cookies and similar tracking technologies to enhance your experience. See our <a href="/cookie-policy">Cookie Policy</a> for details.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>How We Use Your Information</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>To provide, operate, and maintain our website</li>
<li>To improve, personalize, and expand our content</li>
<li>To understand how visitors use our website</li>
<li>To communicate with you regarding inquiries</li>
<li>To display relevant advertisements</li>
<li>To prevent fraud and ensure security</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Google AdSense &amp; Advertising</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy uses Google AdSense to display advertisements. Google AdSense uses cookies to serve ads based on your prior visits to this website or other websites. Google\'s use of advertising cookies enables it and its partners to serve ads based on your visit to our site and/or other sites on the Internet. You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer">Google Ads Settings</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Analytics</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use Google Analytics and similar analytics services to track and analyze website traffic. These services may collect information such as how often users visit, what pages they visit, and what other sites they used prior to coming to our site. We use this information to improve our website and content.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Third-Party Links</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website may contain links to third-party websites and services. We are not responsible for the privacy practices or content of these external sites. We encourage you to review the privacy policies of any third-party sites you visit.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Your Rights (GDPR)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you are a resident of the European Economic Area (EEA), you have certain data protection rights including:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>The right to access, update, or delete your personal information</li>
<li>The right to rectification</li>
<li>The right to object to processing</li>
<li>The right to restriction of processing</li>
<li>The right to data portability</li>
<li>The right to withdraw consent</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>To exercise any of these rights, please contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Data Security</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Children\'s Privacy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website is not intended for children under the age of 13. We do not knowingly collect personal information from children.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Changes to This Policy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact Us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have questions about this Privacy Policy, please contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a> or <a href="mailto:akrammarri865@gmail.com">akrammarri865@gmail.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Terms and Conditions page content.
 */
function stb_page_terms_conditions() {
    return array(
        'title'   => 'Terms and Conditions',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Welcome to Factaxy (factaxy.com). By accessing and using this website, you agree to comply with and be bound by the following terms and conditions. If you do not agree with any part of these terms, please do not use our website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Website Usage</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>You may use this website for lawful purposes only. You agree not to use the site in any way that could damage, disable, or impair the website or interfere with other users\' access. All content on Factaxy is provided for informational and educational purposes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Intellectual Property</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All content on this website, including text, graphics, logos, images, tools, and software, is the property of Factaxy or its content providers and is protected by international copyright laws. You may not reproduce, distribute, modify, or republish any content without prior written permission.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Prohibited Activities</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>Using the website for any unlawful purpose</li>
<li>Attempting to gain unauthorized access to any part of the website</li>
<li>Scraping, data mining, or extracting content without permission</li>
<li>Uploading malicious code, viruses, or harmful material</li>
<li>Impersonating another person or entity</li>
<li>Using automated tools to access the website without permission</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Content Accuracy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>While we strive to provide accurate and up-to-date information, Factaxy makes no warranties or representations about the completeness, reliability, or accuracy of any content on this website. Content is provided "as is" without any guarantees.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>External Links</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website may contain links to external websites that are not operated by us. We have no control over the content and practices of these sites and cannot be held responsible for their privacy policies or content.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Limitation of Liability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>To the fullest extent permitted by law, Factaxy and its owner shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of this website, its content, tools, or services.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Changes to Terms</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We reserve the right to modify these terms at any time. Changes take effect immediately upon posting. Your continued use of the website after changes constitutes acceptance of the new terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For questions about these Terms and Conditions, contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Disclaimer page content.
 */
function stb_page_disclaimer() {
    return array(
        'title'   => 'Disclaimer',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>General Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The information provided on Factaxy (factaxy.com) is for general informational and educational purposes only. All content on this website is published in good faith and is intended to help users learn, explore, and make informed decisions.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>No Professional Advice</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The content on Factaxy does not constitute professional, legal, financial, medical, or technical advice. You should not rely solely on information found on this website for making important decisions. Always consult with qualified professionals for advice tailored to your specific situation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Accuracy of Information</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>While we make every effort to keep information accurate and up to date, we make no representations or warranties of any kind about the completeness, accuracy, reliability, or suitability of the content. Any reliance you place on such information is strictly at your own risk.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>External Links Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This website may contain links to external websites or resources. These links are provided for convenience and informational purposes only. Factaxy does not endorse or assume responsibility for the content, accuracy, or practices of any third-party websites.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Affiliate Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Some links on Factaxy may be affiliate links, meaning we may earn a commission if you click through and make a purchase. This comes at no additional cost to you. For full details, please see our <a href="/affiliate-disclosure">Affiliate Disclosure</a> page.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Tool &amp; Calculator Disclaimer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Online tools and calculators provided on this website are for estimation and informational purposes only. Results may not be 100% accurate and should not be used as a substitute for professional calculations or advice.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have concerns about any content on this website, please contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Cookie Policy page content.
 */
function stb_page_cookie_policy() {
    return array(
        'title'   => 'Cookie Policy',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This Cookie Policy explains what cookies are, how Factaxy (factaxy.com) uses them, and how you can manage your cookie preferences.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>What Are Cookies?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you visit a website. They help the website remember your preferences and improve your browsing experience.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>How We Use Cookies</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy uses cookies for the following purposes:</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Essential Cookies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These cookies are necessary for the website to function properly. They enable basic features like page navigation, security, and accessibility.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics Cookies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use Google Analytics to understand how visitors interact with our website. These cookies collect anonymous information about page views, traffic sources, and user behavior to help us improve our content and services.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Advertising Cookies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Google AdSense and other advertising partners use cookies to display relevant ads based on your browsing history. These cookies track your visits across websites to deliver personalized advertisements.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Preference Cookies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These cookies remember your settings and preferences, such as dark/light mode selection, to provide a consistent experience across visits.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Managing Cookies</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>You can control and manage cookies through your browser settings. Most browsers allow you to:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>View what cookies are stored on your device</li>
<li>Delete all or specific cookies</li>
<li>Block cookies from specific or all websites</li>
<li>Set preferences for first-party and third-party cookies</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Please note that disabling cookies may affect the functionality of some features on our website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Browser Settings</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Chrome:</strong> Settings &gt; Privacy and Security &gt; Cookies</li>
<li><strong>Firefox:</strong> Settings &gt; Privacy &amp; Security &gt; Cookies</li>
<li><strong>Safari:</strong> Preferences &gt; Privacy &gt; Manage Website Data</li>
<li><strong>Edge:</strong> Settings &gt; Cookies and Site Permissions</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For questions about our cookie practices, contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Affiliate Disclosure page content.
 */
function stb_page_affiliate_disclosure() {
    return array(
        'title'   => 'Affiliate Disclosure',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Transparency Statement</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>At Factaxy (factaxy.com), transparency is one of our core values. This page discloses how we earn revenue through affiliate partnerships while maintaining editorial independence and honest recommendations.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>What Are Affiliate Links?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Some of the links on Factaxy are affiliate links. This means that if you click on a link and make a purchase or sign up for a service, we may receive a small commission at no additional cost to you. These commissions help us maintain the website, create new content, and develop free tools.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Our Affiliate Partnerships</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy participates in various affiliate programs, which may include but are not limited to:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><strong>Amazon Associates Program:</strong> As an Amazon Associate, we earn from qualifying purchases made through our links to Amazon.com.</li>
<li><strong>Other Retail Affiliates:</strong> We may participate in affiliate programs with eBay, Walmart, and other retail platforms.</li>
<li><strong>Software &amp; SaaS Affiliates:</strong> We may earn commissions from software products, hosting services, and digital tools we recommend.</li>
<li><strong>General Affiliate Networks:</strong> We may work with affiliate networks such as ShareASale, CJ Affiliate, Impact, and others.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Editorial Independence</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our affiliate relationships do not influence our editorial content. We only recommend products and services that we genuinely believe provide value to our readers. Our reviews and recommendations are based on thorough research, testing, and honest assessment regardless of affiliate partnerships.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Your Trust Matters</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We understand that trust is earned. Using our affiliate links is entirely optional and voluntary. You will never pay more by using our links. If you prefer not to use affiliate links, you can navigate directly to the product or service website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have questions about our affiliate partnerships, please email us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * DMCA Policy page content.
 */
function stb_page_dmca_policy() {
    return array(
        'title'   => 'DMCA Policy',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Factaxy (factaxy.com) respects the intellectual property rights of others and expects its users to do the same. We comply with the Digital Millennium Copyright Act (DMCA) and will respond promptly to legitimate copyright infringement claims.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Copyright Infringement Notice</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you believe that content on Factaxy infringes your copyright, please submit a DMCA takedown notice containing the following information:</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li>Your full legal name and contact information (address, phone number, email)</li>
<li>A description of the copyrighted work you believe has been infringed</li>
<li>The URL(s) on our website where the allegedly infringing content is located</li>
<li>A statement that you have a good faith belief that the use is not authorized by the copyright owner, its agent, or the law</li>
<li>A statement, under penalty of perjury, that the information in your notice is accurate and that you are the copyright owner or authorized to act on behalf of the owner</li>
<li>Your physical or electronic signature</li>
</ol>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Takedown Process</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Upon receiving a valid DMCA notice, we will:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Acknowledge receipt of the complaint within 48 hours</li>
<li>Review the claim and verify its validity</li>
<li>Remove or disable access to the infringing content if the claim is valid</li>
<li>Notify the content provider of the takedown, if applicable</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Counter-Notification</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you believe your content was removed in error, you may submit a counter-notification with appropriate documentation proving your right to the content.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Repeat Infringers</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Factaxy reserves the right to terminate accounts or access for users who are repeat copyright infringers.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Submit a DMCA Notice</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Send your DMCA takedown notice to:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Email:</strong> <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a><br><strong>Subject Line:</strong> DMCA Takedown Request - [Description]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Please note that filing a false DMCA claim may result in legal liability. Only submit a claim if you genuinely believe your copyright has been infringed.</p>
<!-- /wp:paragraph -->',
    );
}


/**
 * Editorial Policy page content.
 */
function stb_page_editorial_policy() {
    return array(
        'title'   => 'Editorial Policy',
        'content' => '<!-- wp:paragraph -->
<p><strong>Last Updated:</strong> ' . date( 'F j, Y' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>At Factaxy (factaxy.com), we are committed to delivering accurate, well-researched, and trustworthy content. This Editorial Policy outlines the standards and principles that guide our content creation process.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Content Quality Standards</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Every piece of content published on Factaxy undergoes a rigorous editorial process. Our standards include:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><strong>Originality:</strong> All content is original or properly attributed with source citations.</li>
<li><strong>Accuracy:</strong> Facts, data, and claims are verified through reliable sources.</li>
<li><strong>Clarity:</strong> Content is written in clear, accessible language suitable for a broad audience.</li>
<li><strong>Relevance:</strong> Topics are selected based on reader interest, search demand, and educational value.</li>
<li><strong>Timeliness:</strong> Content is regularly reviewed and updated to reflect current information.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Fact-Checking Process</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Before publication, our content is fact-checked against reputable sources including official documentation, peer-reviewed research, industry reports, and authoritative websites. If information cannot be independently verified, we clearly mark it as opinion or indicate that further research may be needed.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Corrections Policy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We are committed to correcting errors promptly. If you find inaccurate information on our website:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a> with details about the error</li>
<li>We will review the claim and make corrections within 48 hours if verified</li>
<li>Significant corrections will be noted with an update notice on the affected page</li>
<li>Minor typos and formatting issues will be fixed without notice</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Editorial Independence</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our editorial content is independent from our advertising and affiliate partnerships. Sponsored content and affiliate links do not influence our editorial opinions, recommendations, or reviews. When content is sponsored, it is clearly labeled as such.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Transparency</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We believe in being transparent with our readers about:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>How we earn revenue (see our <a href="/affiliate-disclosure">Affiliate Disclosure</a>)</li>
<li>How we collect and use data (see our <a href="/privacy-policy">Privacy Policy</a>)</li>
<li>Our review methodology and criteria</li>
<li>Any potential conflicts of interest</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Author Accountability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All content is reviewed by our editorial team before publication. Authors are accountable for the accuracy and quality of their work. We encourage reader feedback and use it to continuously improve our content standards.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For editorial inquiries, corrections, or feedback, contact us at <a href="mailto:waseem@factaxy.com">waseem@factaxy.com</a>.</p>
<!-- /wp:paragraph -->',
    );
}
