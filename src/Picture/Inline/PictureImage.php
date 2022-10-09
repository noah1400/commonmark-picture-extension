<?php 

namespace N0sz\CommonMark\Picture\Inline;
use League\CommonMark\Node\Inline\AbstractInline;

final class PictureImage extends AbstractInline 
{

    public function __construct(array $attributes)
    {
        parent::__construct();
        $this->data->set('attributes',$attributes);
    }
}