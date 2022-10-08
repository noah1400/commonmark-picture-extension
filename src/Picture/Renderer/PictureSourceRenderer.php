<?php 

namespace N0sz\CommonMark\Picture\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use N0sz\CommonMark\Picture\Inline\PictureSource;

final class PictureSourceRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        PictureSource::assertInstanceOf($node);
        $attr = $node->data->get('attributes');
        echo "\n";
        echo "PictureSourceRenderer::render()\n";
        echo "attr: ";
        var_dump($attr[0]);
        echo "\n";
        return new HtmlElement('source', $attr);
    }

    public function getXmlTagName(Node $node): string
    {
        return 'source';
    }

    public function getXmlAttributes(Node $node): array
    {
        return $node->data->get('attributes');
    }
}