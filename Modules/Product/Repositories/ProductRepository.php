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
        if (isset($data['images']) && $data['images']) {
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
        $dataFiles = json_decode($product->images) ?? [];
        $this->updateData($product, $data);

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
    public function deleteFilesStorage($product)
    {
        if (!empty(json_decode($product->images))) {
            foreach (json_decode($product->images) as $path) {
                $this->deleteFileStorage($path);
            }
        }
    }

    public function deleteImageProduct($product, $path)
    {
        $dataFiles = json_decode($product->images) ?? [];

        $dataFiles= collect($dataFiles)->filter(function ($item) use ($path) {
            return $item != $path;
        })->values()->all();
        $this->deleteFileStorage($path);

        $product->images = json_encode($dataFiles);
        $product->push();
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function deleteFileStorage($path)
    {
        if ($path) {
            if (File::exists('storage/'. $path)) {
                File::delete('storage/'. $path);
            }
        }
    }
}
