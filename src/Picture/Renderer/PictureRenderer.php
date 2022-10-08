<?php 

namespace N0sz\CommonMark\Picture\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use N0sz\CommonMark\Picture\PictureBlock;

final class PictureRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        PictureBlock::assertInstanceOf($node);

        $attr = $node->data->get('attributes');
        return new HtmlElement('picture', $attr, $childRenderer->renderNodes($node->children()));
    }

    public function getXmlTagName(Node $node): string
    {
        return 'picture';
    }

    public function getXmlAttributes(Node $node): array
    {
        return $node->data->get('attributes');
    }

}