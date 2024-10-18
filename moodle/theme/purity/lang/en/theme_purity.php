<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// Theme Constants
$string['pluginname'] = 'Purity';
$string['choosereadme'] = 'Purity Premium Moodle Theme, Created by JoomFX.';


// Theme Settings
$string['configtitle'] = 'Purity Settings';

$string['general_settings'] = 'General';
$string['layout'] = 'Layout';
$string['layout_desc'] = 'Determines the look and feel of the website. The "Classic" layout is the original Purity design that follows the Moodle 3 design concepts. Respectively, the "Modern" layout follows the Moodle 4 design concepts.';
$string['swatch'] = 'Swatch';
$string['swatch_desc'] = 'Determines the look and feel of the website. The "Default" swatch is the original Purity design with clearly elevated elements and extensive box-shadow. The "Flat" swatch offers another perspective with almost no elevation and very descrete box-shadow.';
$string['primarycolor'] = 'Primary Color';
$string['primarycolor_desc'] = 'The primary color for the theme.';
$string['secondarycolor'] = 'Secondary Color';
$string['secondarycolor_desc'] = 'The secondary color for the theme. This is also the background color of the Dark Header, Dark Footer and the Dark NavDrawer.';
$string['bodybgcolor'] = 'Body Background Color';
$string['bodybgcolor_desc'] = 'The background color for the body element.';
$string['gray100'] = 'Gray 100';
$string['gray100_desc'] = 'The gray-100 color for the theme. It is used as a light background color in many elements in the theme.';
$string['gray200'] = 'Gray 200';
$string['gray200_desc'] = 'The gray-200 color for the theme. It is mostly used as a hover background color.';
$string['gray300'] = 'Gray 300';
$string['gray300_desc'] = 'The gray-300 color for the theme.';
$string['gray400'] = 'Gray 400';
$string['gray400_desc'] = 'The gray-400 color for the theme.';
$string['gray500'] = 'Gray 500';
$string['gray500_desc'] = 'The gray-500 color for the theme.';
$string['gray600'] = 'Gray 600';
$string['gray600_desc'] = 'The gray-600 color for the theme. It is used mostly as a text color for text-muted and disabled elements.';
$string['gray700'] = 'Gray 700';
$string['gray700_desc'] = 'The gray-700 color for the theme. It is used as a text color for the body element.';
$string['gray800'] = 'Gray 800';
$string['gray800_desc'] = 'The gray-800 color for the theme. It is used as a text color for the headings.';
$string['gray900'] = 'Gray 900';
$string['gray900_desc'] = 'The gray-900 color for the theme.';
$string['bordercolor'] = 'Border Color';
$string['bordercolor_desc'] = 'The border color for the theme.';
$string['pagetopbgcolor'] = 'PageTop Background Color';
$string['pagetopbgcolor_desc'] = 'The background color for the PageTop section.';
$string['pagetoptextcolor'] = 'PageTop Text Color';
$string['pagetoptextcolor_desc'] = 'The text color for the PageTop section (the Breadcrumbs).';

$string['header_settings'] = 'Header';
$string['headerstyle'] = 'Header Style';
$string['headerstyle_desc'] = 'Determines the look and feel of the Header.';
$string['headercolor'] = 'Header Color Variation';
$string['headercolor_desc'] = 'The header color variation.';
$string['headershowlogo'] = 'Show Header Logo';
$string['headershowlogo_desc'] = 'Determines whether the Header logo should be shown.';
$string['headerlogo'] = 'Header Logo';
$string['headerlogo_desc'] = 'The logo to be used in the Header.';
$string['headershowsitename'] = 'Show Site Name';
$string['headershowsitename_desc'] = 'Determines whether the site name should be shown.';
$string['langmenulocation'] = 'Language Menu Location';
$string['langmenulocation_desc'] = 'Determines where the language menu should be located.';
$string['menubreakpoint'] = 'Menu Breakpoint';
$string['menubreakpoint_desc'] = 'Determines when the Header Menu should turn into a Mobile Menu.
<pre>Large devices (desktops and below) - 1200px
Medium devices (tablets and below) - 992px
Small devices (landscape phones and below) - 768px
Extra small devices (portrait phones and below) - 576px</pre>
';
$string['topbarleftcontent'] = 'Topbar Left Content';
$string['topbarleftcontent_desc'] = 'Use this field to provide the Topbar left content. It can be whatever HTML you want. <strong>Available in Style 2 and Style 3 only!</strong>
<br />
<br />
<strong>Example:</strong>
<br />
<pre>
<code>&lt;div class=&quot;social-icons&quot;&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://facebook.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Facebook&quot;&gt;
        &lt;i class=&quot;fa fa-facebook&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://twitter.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Twitter&quot;&gt;
        &lt;i class=&quot;fa fa-twitter&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;https://www.instagram.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Instagram&quot;&gt;
        &lt;i class=&quot;fa fa-instagram&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://linkedin.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Linkedin&quot;&gt;
        &lt;i class=&quot;fa fa-linkedin&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://dribbble.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Dribbble&quot;&gt;
        &lt;i class=&quot;fa fa-dribbble&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
&lt;/div&gt;</code>
</pre>';
$string['topbarrightcontent'] = 'Topbar Right Content';
$string['topbarrightcontent_desc'] = 'Use this field to provide the Topbar right content. It can be whatever HTML you want. <strong>Available in Style 2 only!</strong>
<br />
<br />
<strong>Example:</strong>
<br />
<pre>
<code>&lt;div class=&quot;header-contacts&quot;&gt;
&lt;div class=&quot;contact-item item-separator&quot;&gt;
  &lt;span class=&quot;contact-icon fa fa-phone&quot;&gt;&lt;/span&gt;
  &lt;span class=&quot;contact-value&quot;&gt;0044 889 555 432&lt;/span&gt;
&lt;/div&gt;
&lt;div class=&quot;contact-item&quot;&gt;
  &lt;span class=&quot;contact-icon fa fa-envelope-o&quot;&gt;&lt;/span&gt;
  &lt;span class=&quot;contact-value&quot;&gt;office@purity.com&lt;/span&gt;
&lt;/div&gt;
&lt;/div&gt;</code>
</pre>';

$string['navdrawer_settings'] = 'NavDrawer';
$string['navdrawercolor'] = 'NavDrawer Color Variation';
$string['navdrawercolor_desc'] = 'The NavDrawer color variation.';
$string['navdrawershowlogo'] = 'Show NavDrawer Logo';
$string['navdrawershowlogo_desc'] = 'Determines whether the NavDrawer logo should be shown.';
$string['navdrawerlogo'] = 'NavDrawer Logo';
$string['navdrawerlogo_desc'] = 'The logo to be used in the NavDrawer.';

$string['footer_settings'] = 'Footer';
$string['footercolor'] = 'Footer Color Variation';
$string['footercolor_desc'] = 'The footer color variation.';
$string['footercontent'] = 'Footer Content';
$string['footercontent_desc'] = 'Use this field to provide the footer content. It can be whatever HTML you want.
<br />
<br />
<strong>Example:</strong>
<br />
<pre>
<code>&lt;div class=&quot;row&quot;&gt;
    &lt;div class=&quot;col-12 col-sm-6 col-lg-3 mb-5 mb-lg-0&quot;&gt;
        &lt;h4 class=&quot;footer-title&quot;&gt;Get in Touch&lt;/h4&gt;
        &lt;ul class=&quot;contacts&quot;&gt;
            &lt;li&gt;
                &lt;i class=&quot;fa fa-home&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;64184 Graham Place
            &lt;/li&gt;
            &lt;li&gt;
                &lt;i class=&quot;fa fa-phone&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;0012 345 6789
            &lt;/li&gt;
            &lt;li&gt;
                &lt;i class=&quot;fa fa-envelope-o&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;office@purity.com
            &lt;/li&gt;
            &lt;li&gt;
                &lt;i class=&quot;fa fa-comments&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;Live Chat
            &lt;/li&gt;
            &lt;li&gt;
                &lt;i class=&quot;fa fa-info-circle&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;More Information
            &lt;/li&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
    &lt;div class=&quot;col-12 col-sm-6 col-lg-3 mb-5 mb-lg-0&quot;&gt;
        &lt;h4 class=&quot;footer-title&quot;&gt;Company&lt;/h4&gt;
        &lt;ul&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;About Us&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Blog&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Our Team&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Contact&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Become a Teacher&lt;/a&gt;
            &lt;/li&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
    &lt;div class=&quot;col-12 col-sm-6 col-lg-3 mb-5 mb-lg-0&quot;&gt;
        &lt;h4 class=&quot;footer-title&quot;&gt;Terms of Use&lt;/h4&gt;
        &lt;ul&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Terms of Service&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Privacy Policy&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Copyright Information&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Licenses&lt;/a&gt;
            &lt;/li&gt;
            &lt;li&gt;
                &lt;a href=&quot;#&quot;&gt;Cookies&lt;/a&gt;
            &lt;/li&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
    &lt;div class=&quot;col-12 col-sm-6 col-lg-3 mb-5 mb-lg-0&quot;&gt;
        &lt;h4 class=&quot;footer-title&quot;&gt;About Us&lt;/h4&gt;
        &lt;div&gt;Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis, porttitor eget turpis. Morbi porta orci et augue sollicitudin cursus ut eget ligula. Etiam eu sapien at purus ultricies tempor. Suspendisse interdum, nibh vel vulputate tristique, massa tellus.&lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;</code>
</pre>
';
$string['footercopyrightcontent'] = 'Footer Copyright Content';
$string['footercopyrightcontent_desc'] = 'Use this field to provide the footer copyright content. It can be whatever HTML you want.
<br />
<br />
<strong>Example:</strong>
<br />
<pre>
<code>&copy; Purity. Designed by &lt;a href=&quot;http://YOUR-WEBSITE.com&quot;&gt;Your Company&lt;/a&gt;.</code>
</pre>';
$string['footersocialicons'] = 'Footer Social Icons';
$string['footersocialicons_desc'] = 'Use this field to provide the footer social icons. It can be whatever HTML you want.
<br />
<br />
<strong>Example:</strong>
<br />
<pre>
<code>&lt;div class=&quot;social-icons&quot;&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://facebook.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Facebook&quot;&gt;
        &lt;i class=&quot;fa fa-facebook&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://twitter.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Twitter&quot;&gt;
        &lt;i class=&quot;fa fa-twitter&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;https://www.instagram.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Instagram&quot;&gt;
        &lt;i class=&quot;fa fa-instagram&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://linkedin.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Linkedin&quot;&gt;
        &lt;i class=&quot;fa fa-linkedin&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
    &lt;a target=&quot;_blank&quot; href=&quot;http://dribbble.com&quot; data-toggle=&quot;tooltip&quot; title=&quot;Dribbble&quot;&gt;
        &lt;i class=&quot;fa fa-dribbble&quot; aria-hidden=&quot;true&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
&lt;/div&gt;</code>
</pre>';

$string['course_settings'] = 'Course';
$string['showcourseindex'] = 'Show Course Index';
$string['showcourseindex_desc'] = 'Determines whether the Course Index drawer should be shown. This option affects the "Classic" layout only, since the Course Index is always shown in the "Modern" layout.';
$string['topicsformatlayout'] = 'Topics Format Layout';
$string['topicsformatlayout_desc'] = 'The layout of the Topics format sections.';
$string['weeksformatlayout'] = 'Weeks Format Layout';
$string['weeksformatlayout_desc'] = 'The layout of the Weeks format sections.';
$string['showsectioncompletionicon'] = 'Show Section Completion Icon';
$string['showsectioncompletionicon_desc'] = 'Determines whether the section completion icon should be shown. If all activities in a section are marked as completed, then the whole section is marked as completed. This option takes effect only if the course layout is collapsible.';
$string['courseprogressbar'] = 'Show Course Progress Bar';
$string['courseprogressbar_desc'] = 'Determines whether the course progress bar should be shown. The course progress bar is rendered in the course index drawer.';
$string['listcourseslayout'] = 'List Courses Layout';
$string['listcourseslayout_desc'] = 'Determines whether the courses should be shown as boxes or as a list.';
$string['showcourseprice'] = 'Show Course Price';
$string['showcourseprice_desc'] = 'Determines whether the course price should be shown. This setting affects only the course cards rendered in the Course Category. For courses rendered in custom blocks please check the block settings.';
$string['coursepricecurrencysymbol'] = 'Course Price Currency Symbol';
$string['coursepricecurrencysymbol_desc'] = 'Moodle displays the currency name (USD) instead of the currency symbol ($). You can change this behaviour by entering the currency symbol in the field above. This setting affects only the course cards rendered in the Course Category. For courses rendered in custom blocks please check the block settings.';
$string['coursepriceaccentcolor'] = 'Course Price Accent Color';
$string['coursepriceaccentcolor_desc'] = 'Determines what the background color of the course price badge should be. This setting affects only the course cards rendered in the Course Category. For courses rendered in custom blocks please check the block settings.';

$string['front_page_settings'] = 'Front Page';
$string['containerwidth'] = 'Container Width';
$string['containerwidth_desc'] = 'Determines whether the page container should be fixed width and centered or if it should be fluid and allocate 100% of the available space.';
$string['pageasfrontpage'] = 'Page as Frontpage';
$string['pageasfrontpage_desc'] = 'Determines whether pages (mod_page) should be treated as Frontpage (container, layout, etc).';
$string['showbreadcrumbs'] = 'Show Breadcrumbs Section';
$string['showbreadcrumbs_desc'] = 'Determines whether the Breadcrumbs section should be shown. You can choose to show it on both Frontpage and Page, you can show it on Page only or you can hide it from both Frontpage and Page.';
$string['showmaincontent'] = 'Show Main Content Section';
$string['showmaincontent_desc'] = 'Determines whether the Main Content section should be shown. You can choose to show it on both Frontpage and Page, you can show it on Page only or you can hide it from both Frontpage and Page.
<br />
Hiding the Main Content section allows you to have pages built entirely with blocks.
<br />
Even if you hide the Main Content section, it will still be shown when editing is enabled so you can easily manage those pages.';
$string['fpfullwidth'] = 'Fullwidth Section';
$string['fpfullwidth_desc'] = '';
$string['fpfullwidthbgcolor'] = 'Background Color';
$string['fpfullwidthbgcolor_desc'] = '';
$string['fpfullwidthbgimage'] = 'Background Image';
$string['fpfullwidthbgimage_desc'] = '';
$string['fpfullwidthbgrepeat'] = 'Background Repeat';
$string['fpfullwidthbgrepeat_desc'] = '';
$string['fpfullwidthbgsize'] = 'Background Size';
$string['fpfullwidthbgsize_desc'] = '';
$string['fpfullwidthbgposition'] = 'Background Position';
$string['fpfullwidthbgposition_desc'] = '';
$string['fpfullwidthbgattachment'] = 'Background Attachment';
$string['fpfullwidthbgattachment_desc'] = '';
$string['fpfullwidthtextcolor'] = 'Text Color';
$string['fpfullwidthtextcolor_desc'] = '';
$string['fpfullwidthheadingcolor'] = 'Heading Color';
$string['fpfullwidthheadingcolor_desc'] = '';
$string['fpintro'] = 'Intro Section';
$string['fpintro_desc'] = '';
$string['fpintrobgcolor'] = 'Background Color';
$string['fpintrobgcolor_desc'] = '';
$string['fpintrobgimage'] = 'Background Image';
$string['fpintrobgimage_desc'] = '';
$string['fpintrobgrepeat'] = 'Background Repeat';
$string['fpintrobgrepeat_desc'] = '';
$string['fpintrobgsize'] = 'Background Size';
$string['fpintrobgsize_desc'] = '';
$string['fpintrobgposition'] = 'Background Position';
$string['fpintrobgposition_desc'] = '';
$string['fpintrobgattachment'] = 'Background Attachment';
$string['fpintrobgattachment_desc'] = '';
$string['fpintrotextcolor'] = 'Text Color';
$string['fpintrotextcolor_desc'] = '';
$string['fpintroheadingcolor'] = 'Heading Color';
$string['fpintroheadingcolor_desc'] = '';
$string['fpfeature'] = 'Feature Section';
$string['fpfeature_desc'] = '';
$string['fpfeaturebgcolor'] = 'Background Color';
$string['fpfeaturebgcolor_desc'] = '';
$string['fpfeaturebgimage'] = 'Background Image';
$string['fpfeaturebgimage_desc'] = '';
$string['fpfeaturebgrepeat'] = 'Background Repeat';
$string['fpfeaturebgrepeat_desc'] = '';
$string['fpfeaturebgsize'] = 'Background Size';
$string['fpfeaturebgsize_desc'] = '';
$string['fpfeaturebgposition'] = 'Background Position';
$string['fpfeaturebgposition_desc'] = '';
$string['fpfeaturebgattachment'] = 'Background Attachment';
$string['fpfeaturebgattachment_desc'] = '';
$string['fpfeaturetextcolor'] = 'Text Color';
$string['fpfeaturetextcolor_desc'] = '';
$string['fpfeatureheadingcolor'] = 'Heading Color';
$string['fpfeatureheadingcolor_desc'] = '';
$string['fputility'] = 'Utility Section';
$string['fputility_desc'] = '';
$string['fputilitybgcolor'] = 'Background Color';
$string['fputilitybgcolor_desc'] = '';
$string['fputilitybgimage'] = 'Background Image';
$string['fputilitybgimage_desc'] = '';
$string['fputilitybgrepeat'] = 'Background Repeat';
$string['fputilitybgrepeat_desc'] = '';
$string['fputilitybgsize'] = 'Background Size';
$string['fputilitybgsize_desc'] = '';
$string['fputilitybgposition'] = 'Background Position';
$string['fputilitybgposition_desc'] = '';
$string['fputilitybgattachment'] = 'Background Attachment';
$string['fputilitybgattachment_desc'] = '';
$string['fputilitytextcolor'] = 'Text Color';
$string['fputilitytextcolor_desc'] = '';
$string['fputilityheadingcolor'] = 'Heading Color';
$string['fputilityheadingcolor_desc'] = '';
$string['fpextension'] = 'Extension Section';
$string['fpextension_desc'] = '';
$string['fpextensionbgcolor'] = 'Background Color';
$string['fpextensionbgcolor_desc'] = '';
$string['fpextensionbgimage'] = 'Background Image';
$string['fpextensionbgimage_desc'] = '';
$string['fpextensionbgrepeat'] = 'Background Repeat';
$string['fpextensionbgrepeat_desc'] = '';
$string['fpextensionbgsize'] = 'Background Size';
$string['fpextensionbgsize_desc'] = '';
$string['fpextensionbgposition'] = 'Background Position';
$string['fpextensionbgposition_desc'] = '';
$string['fpextensionbgattachment'] = 'Background Attachment';
$string['fpextensionbgattachment_desc'] = '';
$string['fpextensiontextcolor'] = 'Text Color';
$string['fpextensiontextcolor_desc'] = '';
$string['fpextensionheadingcolor'] = 'Heading Color';
$string['fpextensionheadingcolor_desc'] = '';
$string['fpadditional'] = 'Additional Section';
$string['fpadditional_desc'] = '';
$string['fpadditionalbgcolor'] = 'Background Color';
$string['fpadditionalbgcolor_desc'] = '';
$string['fpadditionalbgimage'] = 'Background Image';
$string['fpadditionalbgimage_desc'] = '';
$string['fpadditionalbgrepeat'] = 'Background Repeat';
$string['fpadditionalbgrepeat_desc'] = '';
$string['fpadditionalbgsize'] = 'Background Size';
$string['fpadditionalbgsize_desc'] = '';
$string['fpadditionalbgposition'] = 'Background Position';
$string['fpadditionalbgposition_desc'] = '';
$string['fpadditionalbgattachment'] = 'Background Attachment';
$string['fpadditionalbgattachment_desc'] = '';
$string['fpadditionaltextcolor'] = 'Text Color';
$string['fpadditionaltextcolor_desc'] = '';
$string['fpadditionalheadingcolor'] = 'Heading Color';
$string['fpadditionalheadingcolor_desc'] = '';
$string['fpprebottom'] = 'Prebottom Section';
$string['fpprebottom_desc'] = '';
$string['fpprebottombgcolor'] = 'Background Color';
$string['fpprebottombgcolor_desc'] = '';
$string['fpprebottombgimage'] = 'Background Image';
$string['fpprebottombgimage_desc'] = '';
$string['fpprebottombgrepeat'] = 'Background Repeat';
$string['fpprebottombgrepeat_desc'] = '';
$string['fpprebottombgsize'] = 'Background Size';
$string['fpprebottombgsize_desc'] = '';
$string['fpprebottombgposition'] = 'Background Position';
$string['fpprebottombgposition_desc'] = '';
$string['fpprebottombgattachment'] = 'Background Attachment';
$string['fpprebottombgattachment_desc'] = '';
$string['fpprebottomtextcolor'] = 'Text Color';
$string['fpprebottomtextcolor_desc'] = '';
$string['fpprebottomheadingcolor'] = 'Heading Color';
$string['fpprebottomheadingcolor_desc'] = '';
$string['fpbottom'] = 'Bottom Section';
$string['fpbottom_desc'] = '';
$string['fpbottombgcolor'] = 'Background Color';
$string['fpbottombgcolor_desc'] = '';
$string['fpbottombgimage'] = 'Background Image';
$string['fpbottombgimage_desc'] = '';
$string['fpbottombgrepeat'] = 'Background Repeat';
$string['fpbottombgrepeat_desc'] = '';
$string['fpbottombgsize'] = 'Background Size';
$string['fpbottombgsize_desc'] = '';
$string['fpbottombgposition'] = 'Background Position';
$string['fpbottombgposition_desc'] = '';
$string['fpbottombgattachment'] = 'Background Attachment';
$string['fpbottombgattachment_desc'] = '';
$string['fpbottomtextcolor'] = 'Text Color';
$string['fpbottomtextcolor_desc'] = '';
$string['fpbottomheadingcolor'] = 'Heading Color';
$string['fpbottomheadingcolor_desc'] = '';
$string['fpafterbottom'] = 'Afterbottom Section';
$string['fpafterbottom_desc'] = '';
$string['fpafterbottombgcolor'] = 'Background Color';
$string['fpafterbottombgcolor_desc'] = '';
$string['fpafterbottombgimage'] = 'Background Image';
$string['fpafterbottombgimage_desc'] = '';
$string['fpafterbottombgrepeat'] = 'Background Repeat';
$string['fpafterbottombgrepeat_desc'] = '';
$string['fpafterbottombgsize'] = 'Background Size';
$string['fpafterbottombgsize_desc'] = '';
$string['fpafterbottombgposition'] = 'Background Position';
$string['fpafterbottombgposition_desc'] = '';
$string['fpafterbottombgattachment'] = 'Background Attachment';
$string['fpafterbottombgattachment_desc'] = '';
$string['fpafterbottomtextcolor'] = 'Text Color';
$string['fpafterbottomtextcolor_desc'] = '';
$string['fpafterbottomheadingcolor'] = 'Heading Color';
$string['fpafterbottomheadingcolor_desc'] = '';

$string['login_page_settings'] = 'Login Page';
$string['loginpanelposition'] = 'Login Panel Position';
$string['loginpanelposition_desc'] = 'Determines how the Login Panel should be positioned.';
$string['loginpagebgimage'] = 'Login Page Background Image';
$string['loginpagebgimage_desc'] = 'The image to be used as a background on the Login Page.';
$string['loginpageshowlogo'] = 'Show Login Page Logo';
$string['loginpageshowlogo_desc'] = 'Determines whether the Login Page logo should be shown.';
$string['loginpagelogo'] = 'Login Page Logo';
$string['loginpagelogo_desc'] = 'The logo to be used on the Login Page.';

$string['typography_settings'] = 'Typography';
$string['googlefonturl'] = 'Google Font URL';
$string['googlefonturl_desc'] = 'Use this field to provide the Google Font URL which will be loaded in the &#x3C;head&#x3E; of your html.';
$string['bodyfontfamily'] = 'Body Font';
$string['bodyfontfamily_desc'] = 'The font that will be used for the &#x3C;body&#x3E; (for all text elements).';
$string['headingsfontfamily'] = 'Headings Font';
$string['headingsfontfamily_desc'] = 'The font that will be used for the headings (h1, h2, h3... h6).';
$string['bodyfontsize'] = 'Body Font Size';
$string['bodyfontsize_desc'] = 'The font size for the &#x3C;body&#x3E; (for all text elements).';
$string['h1fontsize'] = 'H1 Font Size';
$string['h1fontsize_desc'] = 'The font size for Heading 1.';
$string['h2fontsize'] = 'H2 Font Size';
$string['h2fontsize_desc'] = 'The font size for Heading 2.';
$string['h3fontsize'] = 'H3 Font Size';
$string['h3fontsize_desc'] = 'The font size for Heading 3.';
$string['h4fontsize'] = 'H4 Font Size';
$string['h4fontsize_desc'] = 'The font size for Heading 4.';
$string['h5fontsize'] = 'H5 Font Size';
$string['h5fontsize_desc'] = 'The font size for Heading 5.';
$string['h6fontsize'] = 'H6 Font Size';
$string['h6fontsize_desc'] = 'The font size for Heading 6.';
$string['fontweightlight'] = 'Font Weight Light';
$string['fontweightlight_desc'] = 'The value for the light font-weight. It is usually 300 but depending on the font-family 100 or 200 might look better.';
$string['fontweightnormal'] = 'Font Weight Normal';
$string['fontweightnormal_desc'] = 'The value for the normal font-weight. It is usually 400.';
$string['fontweightbold'] = 'Font Weight Bold';
$string['fontweightbold_desc'] = 'The value for the bold font-weight. It is usually 600 but depending on the font-family 500 might look better.';
$string['fontweightextrabold'] = 'Font Weight Extra Bold';
$string['fontweightextrabold_desc'] = 'The value for the extra bold font-weight. It is usually 700 but depending on the font-family 600 might look better.';

$string['advanced_settings'] = 'Advanced';
$string['googleanalytics'] = 'Google Analytics Code';
$string['googleanalytics_desc'] = 'Use this field to enable Google Analytics on your website. The code format should be like [UA-XXXXX-Y]';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';


// Regions
$string['region-fp-fullwidth'] = 'FP Fullwidth';
$string['region-fp-intro'] = 'FP Intro';
$string['region-fp-feature'] = 'FP Feature';
$string['region-fp-utility'] = 'FP Utility';
$string['region-fp-extension'] = 'FP Extension';
$string['region-fp-additional'] = 'FP Additional';
$string['region-fp-prebottom'] = 'FP Prebottom';
$string['region-fp-bottom'] = 'FP Bottom';
$string['region-fp-afterbottom'] = 'FP Afterbottom';
$string['region-main-top'] = 'Main Top';
$string['region-above-content'] = 'Above Content';
$string['region-below-content'] = 'Below Content';
$string['region-side-pre'] = 'Sidebar Right';
$string['region-main-bottom'] = 'Main Bottom';


// Navigation
$string['coursesections'] = 'Course sections';


// Course
$string['coursetopicgoto'] = 'Go to';
$string['coursecontacts'] = 'Course Contacts';
$string['course_content_title'] = 'Course Content';
$string['course_cost_free'] = 'FREE';
$string['course_progressbar_completed'] = 'Completed';


// Blog
$string['written_by'] = 'Written by';
$string['created_date'] = 'Created Date';
$string['blog_comments'] = 'Comments';


// Other strings
$string['open'] = 'Open';