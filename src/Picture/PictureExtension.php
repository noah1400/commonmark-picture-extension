<?php 

namespace N0sz\CommonMark\Picture;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use N0sz\CommonMark\Picture\Renderer\PictureRenderer;
use N0sz\CommonMark\Picture\Renderer\PictureSourceRenderer;
use N0sz\CommonMark\Picture\Renderer\PictureImageRenderer;
use N0sz\CommonMark\Picture\Inline\PictureSource;
use N0sz\CommonMark\Picture\Inline\PictureImage;


final class PictureExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(new PictureStartParser)
                    ->addRenderer(PictureBlock::class, new PictureRenderer, 0)
                    ->addRenderer(PictureSource::class, new PictureSourceRenderer, 0)
                    ->addRenderer(PictureImage::class, new PictureImageRenderer, 0);
    }
}