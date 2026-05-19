<?php

namespace App\Livewire;

use Filament\Widgets\Widget;
use App\Models\ProductDocument;
use App\Models\InventoryItem;
use App\Models\Document;

class AvailableItemDocuments extends Widget
{
    protected string $view = 'livewire.available-item-documents';

    protected int | string | array $columnSpan = 'full';

    public $id;

    public $documents = [];

    public function mount()
    {
        $this->documents = Document::join('product_documents', 'documents.id', '=', 'product_documents.document_id')
            ->join('products', 'product_documents.product_id', '=', 'products.id')
            ->join('inventory_items', 'products.id', '=', 'inventory_items.product_id')
            ->where('inventory_items.id', $this->id)
            ->select('documents.file_name' , 'documents.file_path')
            ->get();
    }

}
