<?php

namespace PlayBill\Component;


use Jcupitt\Vips;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;
use PlayBill\Utils\Alpha;
use PlayBill\Utils\Color;

class Rect extends AbstractComponent implements ComponentInterface
{
    /**
     * @param Pic $image
     * @return Vips\Image
     * @throws Vips\Exception
     */
    public function run(Image $image)
    {

        $overlay = Image::svgload_buffer(<<<EOF
<svg viewBox="0 0 {$this->options->width} {$this->options->height}">
    <rect x="0" y="0" 
    height="{$this->options->width}" 
    width="{$this->options->width}" 
    fill="{$this->options->fill}" 
    ></rect>
</svg>
EOF
        );
        $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
            ->composite($overlay, "over", [
            'x' => $this->options->left,
            'y' => $this->options->top,
        ]);
        return $image;
//
//        $colors = Color::auto2rgba($fill);
//        $overlay = Image::black(
//            $this->options->width,
//            $this->options->height
//        )->newFromImage([
//            $colors[0], $colors[1], $colors[2]
//        ])->rotate($this->options->angle);
//
//        if ($overlay) {
//            $image = $image->copy(['interpretation' => Vips\Interpretation::SRGB])
//                ->composite($overlay, "over", [
//                    'x' => $this->options->left,
//                    'y' => $this->options->top,
//                ]);
//        }
//        return $image;
    }
}
