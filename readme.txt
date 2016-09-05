== Quick start ==

1. Create posts with the post formats “gallery” and/or “image”—these will be your “portfolio elements”
2. Create a page with the template ”portfolio page“ to show these posts on this page
    - if you want to show only gallery and image posts from a specific category, you can select a category in the customizer section “Portfolio elements“
    - you can also exclude the portfolio elements from the blog here
3. Create pages with the template ”Portfolio category page“ to show only one category from your portfolio elements
    - you can choose the category for each page with the template in the customizer section ”Portfolio category pages“
4. Create a page with the template ”Portfolio archive page“ to display portfolio elements you don’t want to see on the portfolio page or the portfolio category pages
    - to label portfolio elements as archived, you must choose a archive category in the customizer section ”Portfolio archive“ and add this to the posts you want to archive
5. Create a page with a gallery and choose one of the two front page templates. One will show the gallery as a slider, the other one will display one image randomly

== Use portfolio function ==

The Hannover theme gives you the ability to show your posts which have the image or gallery post format on one page as a ”portfolio“.
To do this, you have to create a page with the page template ”portfolio page“.
After that all posts with post format ”gallery“ or ”image“ will be shown as “portfolio elements” on this page as a linked image to the single view of the post.

You can customize that in the customizer in the following ways (the options can be found in the panel ”Theme Options“ and the section “Portfolio elements“:
- instead of showing all posts with the both post formats you can select a category to show only image and gallery posts which are assigned to this category
- you can exclude the portfolio elements from the blog
- you can set the number of elements to show on one page
- you can activate a alternative layout to show the title, one image and the excerpt from each portfolio element instead of the linked image

= Portfolio category pages =

To show a subset of the portfolio elements, you can create pages with the template ”Portfolio category page“.
For each of this pages you will get three controls in the customizer in the section ”Portfolio category pages“ which is only shown if one or more pages with this page template exist:
- you can choose the category to show on the page. Next to the category you choose there the post has to be a portfolio
element—if you chose to use posts from one category as portfolio elements, the post must be assigned to both categories.
- you can set the number of elements to show on one page before pagination appears
- you can activate the alternative layout

= Portfolio archive =

You can create a archive for portfolio elements you don’t want to show up on the portfolio overview and on portfolio category pages.
To do this, create a page with the template ”Portfolio archive page“. After that, you have to choose a archive category in the customizer in the ”Portfolio archive“ section.
Besides that, you can remove the archive category from the category widget, set the number of archived portfolio elements to show on one page and use the alternative layout.

== More ==

= Front page =

You can create a page with a gallery and choose one of the two front page templates. One will show the gallery as a slider, the other one will display one image randomly

= Header image/logo =

The header image from the customizer will be shown instead of the title, so you can upload your logo here

== Changelog ==

Version 1.1.3 – 05.09.2016
---
- removed top margin from header image on home page


Version 1.1.2 – 06.08.2016
---
- further improvements of margins (especially in sidebar and comments section)


Version 1.1.1 – 05.08.2016
---
- small CSS fix.


Version 1.1 – 05.08.2016
---
- Overhaul of Typography (margins, …)


Version 1.0.14 – 16.07.2016
---
- changed “E-Mail” to “Email” in screen reader text for new email icon


Version 1.0.13 – 16.07.2016
---
- portrait images in the slider don’t get stretched to full width if they are smaller and are centered vertically now


Version 1.0.12 – 14.07.2016
---
- added mail icon to social menu
- removed @version tag from functions.php


Version 1.0.11 – 30.05.2016
---
- removed deprecated theme tags from style.css


Version 1.0.10 – 14.02.2016
---
- moved screen reader text span outside the svg element


Version 1.0.9 – 14.02.2016
---
- added clearfix to sidebar widgets
- accessibility improvements with the svg social menu


Version 1.0.8 – 18.01.2016
---
- use translators: comments instead of context gettext functions to give a description
- small fixes


Version 1.0.7 – 13.01.2016
---
- use pre_get_posts hook for excluding portfolio elements from main query


Version 1.0.6 – 02.01.2016
---
- changed double quotes
- included 'no posts found' message if have_posts() doesn't find anything
- require_once instead of require
- PHPDoc: use void correctly for functions that doesn't use return


Version 1.0.5 – 01.01.2016
---
- added changelog notice for 1.0.4
- updated screenshot.png


Version 1.0.4 – 31.12.2015
---
- better readme
- customizer control for header text color works now
- if ( have_posts() ) before while ( have_posts() )
- offset logic from page templates to functions.php
- more theme tags in style.css


Version 1.0.3 – 29.12.2015
---
- small CSS nav fixes


Version 1.0.2 – 28.12.2015
---
- directly use absint() as sanitize callback in customizer.php instead of hannover_sanitize_int()


Version 1.0.1 – 27.12.2015
---
- small CSS fixes for mobile view


Version 1.0 – 27.12.2015
---
- initial release

== Copyright ==

Noto Sans
Licence: Apache License, version 2.0
Source: https://www.google.com/fonts/specimen/Noto+Sans

Owl Carousel
Licence: MIT License
Source: http://www.owlcarousel.owlgraphic.com/

Lightbox Script
Licence: MIT License
Source: http://osvaldas.info/image-lightbox-responsive-touch-friendly

Menu JS
Licence: GPLv2 or later
Source: https://github.com/WordPress/twentysixteen/blob/master/js/functions.js

Genericons icon font, Copyright 2013-2015 Automattic.com
License: GNU GPL, Version 2 (or later)
Source: http://www.genericons.com

Xing Icon from Font Awesome 4.5
Licence: SIL OFL 1.1
Source: http://fontastic.me/

svg4everybody
Licence: CC0 1.0 Universal License
Source: https://github.com/jonathantneal/svg4everybody

Image from Screenshot
Licence: CC0 1.0 Universal
Source: https://unsplash.com/photos/9i9RquPtXsg

---
Hannover WordPress Theme, Copyright 2015 Florian Brinkmann
Hannover is distributed under the terms of the GNU GPL