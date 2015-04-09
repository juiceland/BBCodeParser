[![Build Status](https://travis-ci.org/golonka/BBCodeParser.png?branch=master)](https://travis-ci.org/golonka/BBCodeParser)

# BBCodeParser
BBCodeParser is a standalone library that parses all(?) the common bbcode tags.
The easiest way to install is via composer and is equally as easy to integrate into Laravel 4

The available tags are:

BBCode markup                                | Result                                                  | GitHub representation (if available)
-------------------------------------------- | ------------------------------------------------------- | -------------------------------
[b]Bold[/b]                                  | `<strong>Bold</strong>`                                 | **Bold**
[i]Italic[/i]                                | `<em>Italic</em>`                                       | *Italic*
[u]Underline[/u]                             | `<u>Underline</u>`                                      |
[s]Strike[/s]                                | `<strike>Strike</strike>`                               | ~~Strike~~
[code]Code[/code]                            | `<code>Code</code>`                                     | `Code`
[quote]Quote[/quote]                         | `<blockquote>Quote</blockquote>`                        | <blockquote>Quote</blockquote>
[quote=NN]Named quote[/quote]                | `<blockquote><small>NN</small>Named quote</blockquote>` | NN<blockquote>Named quote</blockquote>
[url]URL[/url]                               | `<a href="URL">URL</a>`                                 | <http://example.com/>
[url=URL]Link[/url]                          | `<a href="URL">Link</a>`                                | [Link](http://example.com/)
[img]URL[/img]                               | `<img src="URL">`                                       |
[size=20]Size[/size]                         | `<span style="font-size: 20px;">size</span>`            |
[color=#eca]Color[/color]                    | `<span style="color: #eca;">color</span>`               |
[center]Centered[/center]                    | `<div style="text-align:center;">Centered</div>`        | <p align="center">Centered</p>
Unordered list: [list][/list]                | `<ul></ul>`                                             |
Numerically ordered list: [list=1][/list]    | `<ol></ol>`                                             |
Alphabetically ordered list: [list=a][/list] | `<ol type="a"></ol>`                                    |
[*]List item                                 | `<li>List item`                                         |
[youtube]Youtube-ID[/youtube]                | `<iframe width="560" height="315" src="//www.youtube.com/embed/Youtube-ID" frameborder="0" allowfullscreen></iframe>` |

## Installation

The easiest way to install the BBCodeParser library is via composer.
If you don´t now what composer is or how you use it you can find more information about that at [their website](http://www.getcomposer.org/).

### Composer

You can find the BBCodeParser class via [Packagist](https://packagist.org/packages/golonka/bbcodeparser).
Require the package in your `` composer.json `` file.

    "golonka/bbcodeparser": "1.3"

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
    $bbcode = new BBCodeParser;
    $bbcode->parse('[b]Bold[/b]'); // <strong>Bold</strong>
    
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
