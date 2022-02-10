<?php
/**
 * @author: Steve Simons, s2@toolcube.com, https://stevesimons.com
 **/

/**
 Loads and modifies an SVG image from https://github.com/devicons/devicon/tree/master/icons
 Modification is inserting solid background path.

 Query String Parameters:
    icon (required)
      Example icon=<icon-group>/<icon-name> or icon=swift/swift-original-wordmark
      Value is "<icon-group>/<icon-name>".svg of the Devicons icon to get.
      The extension (.svg) is ignored if included.
      
    color (optional, default 'ffffff')
      Example color=ff0000
      Fill color to use for icon background. 
      Must be hex values, do not include leading '#'.
*/

/**
 * Set to server writable directory, no trailing slash required.
 * As server writable this should be outside in your document root.
 * If null, nothing will be cached.
 */
define('CacheDirectory', null);

/**
 * URL for original Devicon SVG images. 
 * '%s' used as placeholder for 'icon' value passed.
 */
define('SVG_Source_URL', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/%s.svg');

try {
  // Get icon to try to get - bail if not passed.
  $icon = $_GET['icon'] ?? null;
  if (!$icon) {
	  throw new \Exception('No icon passed');
  }

  // Should only consist of alphanumeric and slash.
  $icon = preg_replace('/(\.svg$|[^\w\d\/-])/i', '', $icon);
  
  // Color. Must be 6 hex digits.
  $color = $_GET['color'] ?? null;
  if (!$color || !preg_match('/^[\da-f]{6}$/i', $color)) {
    $color = 'ffffff';
  }

  // SVG already exist?
  $svg = $haveFile = $file = null;
  if (CacheDirectory && file_exists(CacheDirectory)) {
    // Name cached files <icon-group>_<icon-name>-<color>.svg
    $file = sprintf('%s/%s-%s.svg', rtrim(CacheDirectory, '/'), str_replace('/', '_', $icon), $color);
    // If we've got it, send it
    $haveFile = file_exists($file);
  }

  if (!$haveFile) {
    // Get the contents of the SVG
    $svgURL = sprintf(SVG_Source_URL, $icon);
    $svg = file_get_contents($svgURL);
    // If we got an svg, should have viewBox defined
    if ($svg && preg_match('/viewBox="(\d+) (\d+) (\d+) (\d+)".*?>/i', $svg, $matches)) {
      list($replace, $x, $y, $w, $h) = $matches;
      $backgroundPath = sprintf(
        '<path fill="#%5$s" d="M%1$s %2$s H%3$s V%4$s H%1$sz"/>',
        $x, $y, $w, $h,
        $color
      );
      // Replace found viewBox to end of tag with same but append new path
      $svg = str_replace($replace, $replace . $backgroundPath, $svg);
      // Cache file (if cache setup)
      if ($file) {
        $haveFile = file_put_contents($file, $svg) > 0;
      }
    }
    else {
      throw new \Exception(
        sprintf("Unable to get source icon or not format expected: %s\n%s", $svgURL, $svg)
      );
    }
  }
  
  // If there is a file or we have SVG contents, output it
  if ($haveFile || $svg) {
    header('Content-type: image/svg+xml');
    if (!empty($svg))
      echo $svg;
    else
      readfile($file);
  }
}
catch (\Exception $err) {
  printf('Error: %s', $err->getMessage());
  exit(1);
}
