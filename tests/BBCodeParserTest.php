<?php

use \Golonka\BBCode\BBCodeParser;

class BBCodeParserTest extends PHPUnit_Framework_TestCase {

    public function testBBCodeParserCanBeCreated()
    {
        $b = new BBCodeParser;
        $this->assertNotEmpty($b);
    }

    public function testSingleParsing()
    {
        $tests = array(
            array('in' => 'foo[b]bar[/b]baz', 'expected' => 'foo<strong>bar</strong>baz'),
            array('in' => 'foo[i]bar[/i]baz', 'expected' => 'foo<em>bar</em>baz'),
            array('in' => 'foo[s]bar[/s]baz', 'expected' => 'foo<strike>bar</strike>baz'),
            array('in' => 'foo[size=6]bar[/size]baz', 'expected' => 'foo<font size="6">bar</font>baz'),
            array('in' => 'foo[color=#ff0000]bar[/color]baz', 'expected' => 'foo<font color="#ff0000">bar</font>baz'),
            array('in' => 'foo[color=#eee]bar[/color]baz', 'expected' => 'foo<font color="#eee">bar</font>baz'),
            array('in' => '[center]foobar[/center]', 'expected' => '<div style="text-align:center;">foobar</div>'),
            array('in' => '[left]foobar[/left]', 'expected' => '<div style="text-align:left;">foobar</div>'),
            array('in' => '[right]foobar[/right]', 'expected' => '<div style="text-align:right;">foobar</div>'),
            array('in' => '[quote]foobar[/quote]', 'expected' => '<blockquote>foobar</blockquote>'),
            array('in' => '[quote=golonka]foobar[/quote]', 'expected' => '<blockquote><small>golonka</small>foobar</blockquote>'),
            array('in' => '[url]http://www.aftonbladet.se[/url]', 'expected' => '<a href="http://www.aftonbladet.se">http://www.aftonbladet.se</a>'),
            array('in' => '[url=http://www.example.com]aftonbladet[/url]', 'expected' => '<a href="http://www.example.com">aftonbladet</a>'),
            array('in' => '[img]http://example.com/images/logo.png[/img]', 'expected' => '<img src="http://example.com/images/logo.png">'),
            array('in' => '[list=1][/list]', 'expected' => '<ol></ol>'),
            array('in' => '[list=a][/list]', 'expected' => '<ol type="a"></ol>'),
            array('in' => '[list][/list]', 'expected' => '<ul></ul>'),
            array('in' => '[*]Item 1', 'expected' => '<li>Item 1</li>'),
            array('in' => '[code]<?php echo \'Hello World\'; ?>[/code]', 'expected' => '<code><?php echo \'Hello World\'; ?></code>'),
            array('in' => '[youtube]Nizq4RnsJJo[/youtube]', 'expected' => '<iframe width="560" height="315" src="//www.youtube.com/embed/Nizq4RnsJJo" frameborder="0" allowfullscreen></iframe>'),
        );
        $b = new BBCodeParser;

        foreach ($tests as $test) {
            $result = $b->parse($test['in']);
            $this->assertEquals($result, $test['expected']);
        }
    }

    public function testCompleteBBCodeParser()
    {
        $b = new BBCodeParser;
        $s = '
            [b]bold[/b][i]italic[/i][u]underline[/u][s]line through[/s][size=6]size[/size]
            [color=#eee]color[/color][center]centered text[/center][quote]quote[/quote]
            [quote=golonka]quote[/quote][url]http://www.example.com[/url]
            [url=http://www.example.com]example.com[/url][img]http://example.com/logo.png[/img]
            [list=1]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
            [code]<?php echo \'Hello World\'; ?>[/code]
            [youtube]Nizq4RnsJJo[/youtube]
            [list]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
        ';
        $r = $b->parse($s);
        $this->assertEquals('
            <strong>bold</strong><em>italic</em><u>underline</u><strike>line through</strike><font size="6">size</font>
            <font color="#eee">color</font><div style="text-align:center;">centered text</div><blockquote>quote</blockquote>
            <blockquote><small>golonka</small>quote</blockquote><a href="http://www.example.com">http://www.example.com</a>
            <a href="http://www.example.com">example.com</a><img src="http://example.com/logo.png">
            <ol>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ol>
            <code><?php echo \'Hello World\'; ?></code>
            <iframe width="560" height="315" src="//www.youtube.com/embed/Nizq4RnsJJo" frameborder="0" allowfullscreen></iframe>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ul>
        ', $r);
    }

    public function testOnlyFunctionality()
    {
        $b = new BBCodeParser;

        $b->only('bold', 'underline');
        $this->arrays_are_similar($b->getParsers(), ['image', 'link']);

        $result = $b->parse('[b]Bold[/b] [url]http://example.com[/url] [u]Underline[/u]');
        $this->assertEquals($result, '<strong>Bold</strong> [url]http://example.com[/url] <u>Underline</u>');
    }

    public function testExceptFunctionality()
    {
        $b = new BBCodeParser;

        $b->except('link', 'bold');
        $this->arrays_are_similar(
            $b->getParsers(),
            [
                'italic',
                'underLine',
                'linethrough',
                'color',
                'center',
                'quote',
                'quote',
                'namedlink',
                'orderedlistnumerical',
                'orderedlistalpha',
                'unorderedlist',
                'listitem',
                'code',
                'youtube',
                'linebreak',
            ]
        );

        $result = $b->parse('[b]Bold[/b] [url]http://example.com[/url] [u]Underline[/u]');
        $this->assertEquals($result, '[b]Bold[/b] [url]http://example.com[/url] <u>Underline</u>');
    }

    public function testCustomParser()
    {
        $b = new BBCodeParser;

        $b->setParser('verybold', '/\[verybold\](.*)\[\/verybold\]/', '<strong>VERY $1 BOLD</strong>');

        $result = $b->parse('[verybold]something[/verybold]');

        $this->assertEquals($result, '<strong>VERY something BOLD</strong>');
    }

    public function testIfTagsGetParsedProperlyIfLineBreaksExists()
    {
        $b = new BBCodeParser;
        $result = $b->parse(
            't[b]e[/b]s[b]t[/b]
            [code]Test
            123[/code]'
        );

        $this->assertEquals(
            $result,
            't<strong>e</strong>s<strong>t</strong>
            <code>Test
            123</code>'
        );
    }

    public function testNestedNamedQuotes()
    {
        $b = new BBCodeParser;
        $result = $b->parse('[quote=Xzibit][quote=PimpMyRide]So i put a quote in you quote![/quote]I heard you liked quotes.[/quote]');

        $this->assertEquals(
            $result,
            '<blockquote><small>Xzibit</small><blockquote><small>PimpMyRide</small>So i put a quote in you quote!</blockquote>I heard you liked quotes.</blockquote>'
        );
    }

    public function testNestedQuotes()
    {
        $b = new BBCodeParser;
        $result = $b->parse('[quote][quote]Inception[/quote]Quoteception[/quote]');

        $this->assertEquals(
            $result,
            '<blockquote><blockquote>Inception</blockquote>Quoteception</blockquote>'
        );
    }

    public function testCaseSensitivity()
    {
        $b = new BBCodeParser;
        $result = $b->parse('[B][I][U]More tags === More COOL![/U][/I][/B]', true);

        $this->assertEquals(
            $result,
            '<strong><em><u>More tags === More COOL!</u></em></strong>'
        );
    }

    public function testCaseSensitivityHelperFunctions()
    {
        $b = new BBCodeParser;
        $result = $b->parseCaseSensitive('[b]Bold[/b] [I]Italic[/I]');
        $this->assertEquals($result, '<strong>Bold</strong> [I]Italic[/I]');

        $b = new BBCodeParser;
        $result = $b->parseCaseInsensitive('[b]Bold[/b] [I]Italic[/I]');
        $this->assertEquals($result, '<strong>Bold</strong> <em>Italic</em>');
    }

    public function testRemovalOfBBCodeTags()
    {
        $b = new BBCodeParser;
        $result = $b->stripBBCodeTags('[u]Magpie[/u] is one [b]beautiful[/b] bird!');
        $this->assertEquals($result, 'Magpie is one beautiful bird!');

        $b = new BBCodeParser;
        $result = $b->stripBBCodeTags('[QUOTE=Magpie]I am one [B]beautiful[/B] bird![/QUOTE]');
        $this->assertEquals($result, 'I am one beautiful bird!');
    }

    protected function arrays_are_similar($a, $b) {
        // if the indexes don't match, return immediately
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }
        // we know that the indexes, but maybe not values, match.
        // compare the values between the two arrays
        foreach($a as $k => $v) {
            if ($v !== $b[$k]) {
                return false;
            }
        }
        // we have identical indexes, and no unequal values
        return true;
    }

}
