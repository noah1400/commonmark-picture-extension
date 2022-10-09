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
    }

    private function getAttributes(string $attributes_str): array
    {
        $splitted = explode(',', $attributes_str);
        // remove all quotes except that are escaped
        $splitted = array_map(function ($item) {
            return preg_replace('/(?<!\\\\)((?:\\\\\\\\)*)"/', '', $item);
        }, $splitted);
        // attributes can be as a key:value pair or just a key
        $attributes = array_map(function ($item) {

            $pair = explode(':', $item, 2);
            if (empty($pair[0])) $pair[0] = 'null';
            if (count($pair) === 2) {
                return [trim($pair[0]) => trim($pair[1])];
            } else {
                return [trim($pair[0]) => trim($pair[0])];
            }
        }, $splitted);
        // flatten the array to a single level
        $attributes = array_reduce($attributes, 'array_merge', []);
        return $attributes;
    }

    private function parseLine(string $line): void
    {
        if (!empty($line)) {
            // source regex
            $re = "/\+\s*([a-zA-Z0-9_.]+)(?:\s*{(.*)}){0,1}/s";
            # group 1: filename
            # group 2: attributes
            $found = preg_match($re, $line, $matches, PREG_OFFSET_CAPTURE, 0);
            if ($found) {
                $filename = $matches[1][0];
                if (isset($matches[2][0]) && !empty($matches[2][0])) {
                    $attributes = $this->getAttributes($matches[2][0]);
                } else {
                    $attributes = [];
                }
                if (!empty($filename)) {
                    // add filename as srcset attribute to attributes
                    $attributes['srcset'] = $filename;
                }
                $source = new PictureSource($attributes);
                $this->block->appendChild($source);
            } else {
                // image regex
                $re = "/\-\s*([a-zA-Z0-9_.]+)(?:\s*{(.*)}){0,1}/s";
                # group 1: filename
                # group 2: attributes
                $found = preg_match($re, $line, $matches, PREG_OFFSET_CAPTURE, 0);
                if ($found) {
                    $filename = $matches[1][0];
                    if (isset($matches[2][0]) && !empty($matches[2][0])) {
                        $attributes = $this->getAttributes($matches[2][0]);
                    } else {
                        $attributes = [];
                    }
                    if (!empty($filename)) {
                        // add filename as src attribute to attributes
                        $attributes['src'] = $filename;
                    }
                    $image = new PictureImage($attributes);
                    $this->block->appendChild($image);
                }
            }
        }
    }
}
