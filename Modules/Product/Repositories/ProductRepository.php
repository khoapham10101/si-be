<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;

class ProductRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Product::query()
                ->with(['brand']);
    }

    /**
     * @param array $data
     * @return Product
     */
    public function create($data)
    {

        $product = new Product;

        $this->updateData($product, $data);
        if ($data['images']) {
            $dataFiles = [];
            foreach ($data['images'] as $file) {
                $path = Storage::disk('public')->put(Product::PATH_FILE, $file);
                $dataFiles[] = $path;
            }

            $product->images = json_encode($dataFiles);
        }

        $product->push();

        /** @var Product $refreshed */
        $refreshed = $this->getQuery()->find($product->id);

        return $refreshed;
    }

    /**
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update($product, $data)
    {
        $this->deleteFileStorage($product);
        $this->updateData($product, $data);

        $dataFiles = [];
        if (isset($data['images']) && $data['images']) {
            foreach ($data['images'] as $file) {
                $path = Storage::disk('public')->put(Product::PATH_FILE, $file);
                $dataFiles[] = $path;
            }
        }

        $product->images = json_encode($dataFiles);
        $product->push();

        /** @var Product $refreshed */
        $refreshed = $this->getQuery()->find($product->id);
        return $refreshed;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function deleteFileStorage($product)
    {
        if (!empty(json_decode($product->images))) {
            foreach (json_decode($product->images) as $path) {
                if (File::exists('storage/'. $path)) {
                    File::delete('storage/'. $path);
                }
            }
        }
    }

}
