<?php 

namespace N0sz\CommonMark\Picture\Renderer;
use N0sz\CommonMark\Picture\Inline\PictureImage;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;

final class PictureImageRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        PictureImage::assertInstanceOf($node);

        $attr = $node->data->get('attributes');
        return new HtmlElement('img', $attr);
    }

    public function getXmlTagName(Node $node): string
    {
        return 'img';
    }

    public function getXmlAttributes(Node $node): array
    {
        return $node->data->get('attributes');
    }
}