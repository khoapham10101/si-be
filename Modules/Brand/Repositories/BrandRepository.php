<?php

namespace Modules\Brand\Repositories;

use App\Repositories\BaseRepository;
use Modules\Brand\Entities\Brand;

class BrandRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Brand::query();
    }

    /**
     * @param array $data
     * @return Brand
     */
    public function create($data)
    {

        $brand = new Brand;

        $this->updateData($brand, $data);
        $brand->push();

        /** @var Brand $refreshed */
        $refreshed = $this->getQuery()->find($brand->id);

        return $refreshed;
    }

    /**
     * @param Brand $brand
     * @param array $data
     * @return Brand
     */
    public function update($brand, $data)
    {
        $this->updateData($brand, $data);
        $brand->push();

        /** @var Brand $refreshed */
        $refreshed = $this->getQuery()->find($brand->id);
        return $refreshed;
    }

    /**
     * @param array $data
     * @return Collection|Brand[]
     */
    public function dropdown($data)
    {
        $query = $this->getQuery();

        if (!empty($data['name'] ?? null)) {
            $fields = ['name'];

            $query->where(function($query) use ($fields, $data) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'like' , '%'. $data['name'] .'%');
                }
            });
        }

        return $query->limit(config('app.dropdown.limit'))->get();
    }

}
