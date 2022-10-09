# n0sz/commonmark-picture-extension
This library adds support of `<picture>` tags to [league/commonmark](https://github.com/thephpleague/commonmark)
# Installation
This project can be install via composer:
```
composer require n0sz/commonmark-picture-extension
```
# Usage
```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use N0sz\CommonMark\Picture\PictureExtension;

$environment = new Environment();
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new PictureExtension());
```
# Syntax
Code:
```txt
[[[
+ img_1 {media:"(min-width:650px)"}
+ img_2 {media:"(min-width:465px)"}
- img_3
]]]
```
Result:
```html
<picture>
<source media="(min-width:650px)" srcset="img_1" />
<source media="(min-width:465px)" srcset="img_2" />
<img src="img_3" />
</picture>
```
