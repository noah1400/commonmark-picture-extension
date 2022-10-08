<?php

namespace N0sz\CommonMark\Picture;

use N0sz\CommonMark\Picture\Inline\PictureImage;
use N0sz\CommonMark\Picture\Inline\PictureSource;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockContinueParserWithInlinesInterface;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\InlineParserEngineInterface;



final class PictureParser extends AbstractBlockContinueParser implements BlockContinueParserWithInlinesInterface
{
    private PictureBlock $block;

    private string $blockEnd = ']]]';

    public function __construct()
    {
        $this->block = new PictureBlock;
    }

    public function getBlock(): PictureBlock
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        $line = $cursor->getLine();
        if ($line === $this->blockEnd) return BlockContinue::finished();

        if (!empty($line)) {
            $this->parseLine($line);
        }

        return BlockContinue::at($cursor);
    }

    public function parseInlines(InlineParserEngineInterface $inlineParser): void
    {
        // foreach ($this->body as $element) {
        //     $this->block->appendChild($element);
        // }
    }

    private function getAttributes(string $attributes_str): array
    {
        $splitted = explode(',', $attributes_str);
        // attributes can be as a key:value pair or just a key
        $attributes = array_map(function ($item) {

            $pair = explode(':', $item);
            // remove all quotes except that are escaped
            $pair = array_map(function ($item) {
                return preg_replace('/(?<!\\\\)((?:\\\\\\\\)*)"/', '', $item);
            }, $pair);
            if (count($pair) === 2) {
                return [$pair[0] => $pair[1]];
            } else {
                return [$pair[0] => null];
            }
        }, $splitted);
        return $attributes;
    }

    private function parseLine(string $line): void
    {
        if (!empty($line)) {
            // source regex
            $re = "/\+\s*([a-zA-Z0-9_.]+)(?:\s*{(.*)})/s";
            # group 1: filename
            # group 2: attributes
            $found = preg_match($re, $line, $matches, PREG_OFFSET_CAPTURE, 0);
            if ($found) {
                $filename = $matches[1][0];
                $attributes_str = $matches[2][0];


                $attributes = $this->getAttributes($attributes_str);
                $source = new PictureSource($filename, $attributes);
                $this->block->appendChild($source);
            } else {
                // image regex
                $re = "/\-\s*([a-zA-Z0-9_.]+)(?:\s*{(.*)})/s";
                # group 1: filename
                # group 2: attributes
                $found = preg_match($re, $line, $matches, PREG_OFFSET_CAPTURE, 0);
                if ($found) {
                    $filename = $matches[1][0];
                    $attributes_str = $matches[2][0];

                    $attributes = $this->getAttributes($attributes_str);

                    $image = new PictureImage($filename, $attributes);
                    $this->block->appendChild($image);
                }
            }
        }
    }
}
