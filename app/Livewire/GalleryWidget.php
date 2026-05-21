<?php

namespace App\Livewire;

use Filament\Widgets\Widget;
use App\Models\Product;

class GalleryWidget extends Widget
{
    protected string $view = 'livewire.gallery-widget';

    public ?int $productId = null;

    
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $product = Product::with('gallery.images')->find($this->productId);

        return [
            'images' => $product?->gallery?->images ?? collect(),
            'headerImage' => $product?->header_image,
        ];
    }
}
