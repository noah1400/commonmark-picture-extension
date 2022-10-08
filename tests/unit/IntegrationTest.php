<?php 

namespace N0sz\CommonMark\Picture\Tests\Unit;

use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Renderer\HtmlRenderer;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use N0sz\CommonMark\Picture\PictureExtension;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{

    /**
     * @dataProvider provideMarkdown
     */
    public function testPicture($string, $expected) {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new PictureExtension());
        $parser = new MarkdownParser($environment);
        $renderer = new HtmlRenderer($environment);
        $document = $parser->parse($string);
        $html = (string)$renderer->renderDocument($document);
        $this->assertEquals($expected, $html);
    }

    public function provideMarkdown() {
        return [
            ["[[[\n]]]", "<picture></picture>\n"],
            ["[[[]]]", "<p>[[[]]]</p>\n"],
            ["[[[\n+ image1 {media: asd}\n+ image2 {media: sd}\n- image3 {{size: gffd}}\n]]]",
            '<picture><source media="123" src="image1" /><source media="23" src="image2" /><img size="123" src="image3" /></picture>']
        ];
    }
}