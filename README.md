[![Build Status](https://travis-ci.org/golonka/BBCodeParser.png?branch=master)](https://travis-ci.org/golonka/BBCodeParser)
[![Coverage Status](https://coveralls.io/repos/golonka/BBCodeParser/badge.png)](https://coveralls.io/r/golonka/BBCodeParser)

# BBCodeParser
BBCodeParser is a standalone library that parses all(?) the common bbcode tags.
The easiest way to install is via composer and is equally as easy to integrate into Laravel 4

The available tags are:

- [b][/b] Bold
- [i][/i] Italic
- [u][/u] Underline
- [s][/s] Line through
- [size=4][/size] Font size
- [color=#eee][/color] Font color
- [center][/center] Center
- [quote][/quote] Quote
- [quote=John Doe][/] Named quote
- [url][/url] Link
- [url=http://example.com]example.com[/url] Named link
- [img]http://example.com]example.com/logo.png[/img] Image
- [ol][/ol] Ordered list
- [ul][/ul] Unordered list
- [*] List item
- [code][/code] Code
- [youtube][/youtube] Youtube

## Installation

The easiest way to install the BBCodeParser library is via composer.
If you don´t now what composer is or how you use it you can find more information about that at [their website](http://www.getcomposer.org/).

### Composer

You can find the BBCodeParser class via [Packagist](https://packagist.org/packages/golonka/bbcodeparser).
Require the package in your `` composer.json `` file.

    "golonka/bbcodeparser": "1.1.*"

Then you run install or update to download your new requirement

    php composer.phar install

or

    php composer.phar update

Now you are able to require the vendor/autoload.php file to PSR-0 autoload the library.

### Example
 
    // include composer autoload
    require 'vendor/autoload.php';
    
    // import the BBCodeParser Class
    use Golonka\BBCode\BBCodeParser;

    // Lets parse!
    $bbcode = BBCodeParser::parse('[b]Bold[/b]');
If you´re a fan of Laravel 4 then the integration is made in a blink of an eye. 
We will go through how that is done below. 

## Laravel 4 integration

The BBCodeParser Class has optional Laravel 4 support and comes with a Service Provider and Facades for easy integration. After you have done the installation correctly, just follow the instructions.

Open your Laravel config file config/app.php and add the following lines.

In the ``$providers `` array add the service providers for this package.

    'Golonka\BBCode\BBCodeParserServiceProvider'

Add the facade of this package to the `` $aliases `` array.

    'BBCode' => 'Golonka\BBCode\Facades\BBCodeParser'

Now the BBCodeParser Class will be auto-loaded by Laravel.

### Example

By default all tags will be parsed

    BBCode::parse('[b]bold[/b][i]italic[/i]');

If you would like to use only some tags when you parse you can do that by doing like this 

    // In this case the [i][/i] tag will not be parsed
    BBCode::only('bold')->parse('[b]bold[/b][i]italic[/i]');

or

    // In this case all tags except [b][/b] will be parsed
    BBCode::except('bold')->parse('[b]bold[/b][i]italic[/i]');

## Custom Parsers

You can add new custom parsers or overwrite existing parsers.

    // name, pattern, replace
    BBCode::setParser('mailurl', '/\[mailurl\](.*)\[\/mailurl\]/', '<a href="mailto:$1">$1</a>');
