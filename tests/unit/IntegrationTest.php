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
            ["[[[\n+ image1 {media: asd}\n+ image2 {media: sd}\n- image3 {size: gffd}\n]]]",
            '<picture><source media="asd" srcset="image1" /><source media="sd" srcset="image2" /><img size="gffd" src="image3" /></picture>'."\n"],
            ["[[[\n+ img_1 {media: \"(min-width: 1000px) and (max-width: 2000px)\"}\n+ img_2 {media: \"(min-width: 2000px)\"}\n- img_3 {size: 1000px}\n]]]",
            '<picture><source media="(min-width: 1000px) and (max-width: 2000px)" srcset="img_1" /><source media="(min-width: 2000px)" srcset="img_2" /><img size="1000px" src="img_3" /></picture>'."\n"],
            ["[[[\n+ img_3 {sizes} \n+ img_3\n- img_3 \n]]]","<picture><source sizes=\"sizes\" srcset=\"img_3\" /><source srcset=\"img_3\" /><img src=\"img_3\" /></picture>\n"],
            ["[[[\n+ flower.webp {type:image/webp}\n+ flower.jpg {type:image/jpeg}\n- flower.jpg {alt:\"\"}\n]]]",'<picture><source type="image/webp" srcset="flower.webp" /><source type="image/jpeg" srcset="flower.jpg" /><img alt="" src="flower.jpg" /></picture>'."\n"],
            ["[[[\n+ img_1 {media:\"(min-width:650px)\"}\n+ img_2 {media:\"(min-width:465px)\"}\n- img_3\n]]]",
            "<picture><source media=\"(min-width:650px)\" srcset=\"img_1\" /><source media=\"(min-width:465px)\" srcset=\"img_2\" /><img src=\"img_3\" /></picture>\n"]
        ];
    }
}