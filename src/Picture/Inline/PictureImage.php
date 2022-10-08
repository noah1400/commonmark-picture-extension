<?php 

namespace N0sz\CommonMark\Picture\Inline;
use League\CommonMark\Node\Inline\AbstractInline;

final class PictureImage extends AbstractInline 
{
    public string $filename;
    public array $attributes;

    public function __construct(string $filename, array $attributes)
    {
        parent::__construct();
        $this->filename = $filename;
        $this->attributes = $attributes;
        $this->data->set('attributes',$attributes);
    }
}