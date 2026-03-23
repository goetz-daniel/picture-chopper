# Picture Chopper

Download protection for images. Splits a photo into a grid of randomly rotated, shuffled, base64-encoded tiles and reassembles them visually with CSS Grid.

## How it works

1. The image is divided into a grid of square tiles
2. Each tile is randomly rotated (90°, 180°, or 270°)
3. Tiles are encoded as base64 WebP and shuffled in the DOM
4. CSS Grid places each tile in its correct cell and counter-rotates it back

## Usage

```php
require 'chopper.php';

// protect an image with default tile density
echo picture_chopper('photo.jpg');

// more tiles = stronger protection
echo picture_chopper('photo.jpg', 8);
```

## License

MIT
