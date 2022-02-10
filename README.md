# Insert solid background to a Devicon SVG icon

Loads and modifies an SVG image from https://github.com/devicons/devicon/tree/master/icons

## Installation and Configuration

This is a php endpoint. Copy to any server setup to parse php (should support 5x and higher).

Requests can be cached, to do so edit the php file and find the lines:
```
/**
 * Set to server writable directory, no trailing slash required.
 * As server writable this should be outside in your document root.
 * If null, nothing will be cached.
 */
define('CacheDirectory'', null);
```
Change the `null` value to an existing directory that the webserver user has writing permissions.

For example if the the php file were at the document root, and the cache directory was created in
the parent of the document root:
```
define('CacheDirectory', dirname(__DIR__) . '/cache-icons');
```

## Usage
### Query String Parameters:
#### icon (required)
Example icon=&lt;icon-group&gt;/&lt;icon-name&gt; or icon=swift/swift-original-wordmark<br>
Value is "&lt;icon-group&gt;/&lt;icon-name&gt;".svg of the Devicons icon to get.<br>
The extension (.svg) is ignored if included.

#### color (optional, default 'ffffff')
Example color=ff0000<br>
Fill color to use for icon background.<br>
Must be hex values, do not include leading '#'.

---

#### Examples
The default bitbucket/bitbucket-original icon (which has a transparent background):
```
<img height="200" alt="original" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bitbucket/bitbucket-original.svg">
```
<img height="200" alt="original" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bitbucket/bitbucket-original.svg">

Change the reference to the hosted php page as the src for the icon you want:
```
<img height="200" alt="icon" src="https://my-site/gh-svg.php?icon=bitbucket/bitbucket-original">
```
<img height="200" alt="icon" src="https://stevesimons.com/gh-svg.php?icon=bitbucket/bitbucket-original">

or with red background
```
<img height="200" alt="icon" src="https://my-site/gh-svg.php?icon=bitbucket/bitbucket-ogiginal&color=ff0000">
```
<img height="200" alt="icon" src="https://stevesimons.com/gh-svg.php?icon=bitbucket/bitbucket-original&color=ff0000">


