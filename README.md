# Hannover 
**Contributors:** FlorianBrinkmann  
**Requires at least:** 4.5  
**Tested up to:** 4.9.4  
Requires PHP: 5.4


## Description 


## Quick start 

1. Create posts with the post formats »gallery« and/or »image« – they will be your »portfolio elements«
2. Go to »Design« › »Customize« › »Theme Options« and create a portfolio page.


## Changelog 


### 2.0.0 –  

**Changed**

* Moved the whole portfolio settings into the customizer – no more page templates.

**Removed**

* Hannover Social Menu feature. This can be better done by a plugin (for example, https://wordpress.org/plugins/svg-social-menu/)


### 1.1.3 – 05.09.2016 
* removed top margin from header image on home page



### 1.1.3 – 05.09.2016 
* removed top margin from header image on home page



### 1.1.2 – 06.08.2016 
* further improvements of margins (especially in sidebar and comments section)



### 1.1.1 – 05.08.2016 
* small CSS fix.



### 1.1 – 05.08.2016 
* Overhaul of Typography (margins, …)



### 1.0.14 – 16.07.2016 
* changed »E-Mail« to »Email« in screen reader text for new email icon



### 1.0.13 – 16.07.2016 
* portrait images in the slider don’t get stretched to full width if they are smaller and are centered vertically now



### 1.0.12 – 14.07.2016 
* added mail icon to social menu
* removed @version tag from functions.php



### 1.0.11 – 30.05.2016 
* removed deprecated theme tags from style.css



### 1.0.10 – 14.02.2016 
* moved screen reader text span outside the svg element



### 1.0.9 – 14.02.2016 
* added clearfix to sidebar widgets
* accessibility improvements with the svg social menu



### 1.0.8 – 18.01.2016 
* use translators: comments instead of context gettext functions to give a description
* small fixes



### 1.0.7 – 13.01.2016 
* use pre_get_posts hook for excluding portfolio elements from main query



### 1.0.6 – 02.01.2016 
* changed double quotes
* included 'no posts found' message if have_posts() doesn't find anything
* require_once instead of require
* PHPDoc: use void correctly for functions that doesn't use return



### 1.0.5 – 01.01.2016 
* added changelog notice for 1.0.4
* updated screenshot.png



### 1.0.4 – 31.12.2015 
* better readme
* customizer control for header text color works now
* if ( have_posts() ) before while ( have_posts() )
* offset logic from page templates to functions.php
* more theme tags in style.css



### 1.0.3 – 29.12.2015 
* small CSS nav fixes



### 1.0.2 – 28.12.2015 
* directly use absint() as sanitize callback in customizer.php instead of hannover_sanitize_int()



### 1.0.1 – 27.12.2015 
* small CSS fixes for mobile view



### 1.0 – 27.12.2015 
* initial release
