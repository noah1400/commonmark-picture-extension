<?php 

namespace N0sz\CommonMark\Picture;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use N0sz\CommonMark\Picture\PictureParser;


final class PictureStartParser implements BlockStartParserInterface
{
    private string $blockStart = '[[[';

    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        if ($cursor->isIndented() ||
            $cursor->getIndent() > 0 ||
            $cursor->getLine() !== $this->blockStart
        ) {
            return BlockStart::none();
        }else{
            return BlockStart::of(new PictureParser);
        }
    }
}