# View Helper - HeadLink

## Introduction

The *HTML* **&lt;link&gt;** element is increasingly used for linking a variety of resources for your
site: stylesheets, feeds, favicons, trackbacks, and more. The `HeadLink` helper provides a simple
interface for creating and aggregating these elements for later retrieval and output in your layout
script.

The `HeadLink` helper has special methods for adding stylesheet links to its stack:

- `appendStylesheet($href, $media, $conditionalStylesheet, $extras)`
- `offsetSetStylesheet($index, $href, $media, $conditionalStylesheet, $extras)`
- `prependStylesheet($href, $media, $conditionalStylesheet, $extras)`
- `setStylesheet($href, $media, $conditionalStylesheet, $extras)`

The `$media` value defaults to 'screen', but may be any valid media value. `$conditionalStylesheet`
is a string or boolean `FALSE`, and will be used at rendering time to determine if special comments
should be included to prevent loading of the stylesheet on certain platforms. `$extras` is an array
of any extra values that you want to be added to the tag.

Additionally, the `HeadLink` helper has special methods for adding 'alternate' links to its stack:

- `appendAlternate($href, $type, $title, $extras)`
- `offsetSetAlternate($index, $href, $type, $title, $extras)`
- `prependAlternate($href, $type, $title, $extras)`
- `setAlternate($href, $type, $title, $extras)`

The `headLink()` helper method allows specifying all attributes necessary for a **&lt;link&gt;**
element, and allows you to also specify placement -- whether the new element replaces all others,
prepends (top of stack), or appends (end of stack).

The `HeadLink` helper is a concrete implementation of the Placeholder helper
&lt;zend.view.helpers.initial.placeholder&gt;.

## Basic Usage

You may specify a **headLink** at any time. Typically, you will specify global links in your layout
script, and application specific links in your application view scripts. In your layout script, in
the &lt;head&gt; section, you will then echo the helper to output it.

```php
<?php
// setting links in a view script:
$this->headLink(array('rel' => 'icon', 'href' => '/img/favicon.ico'), 'PREPEND')
     ->appendStylesheet('/styles/basic.css')
     ->prependStylesheet(
         '/styles/moz.css',
         'screen',
         true,
         array('id' => 'my_stylesheet')
     );

// rendering the links from the layout:
echo $this->headLink();
?>
```

Output:

```php
<link href="/styles/moz.css" media="screen" rel="stylesheet" type="text/css" id="my_stylesheet">
<link href="/img/favicon.ico" rel="icon">
<link href="/styles/basic.css" media="screen" rel="stylesheet" type="text/css">
```
