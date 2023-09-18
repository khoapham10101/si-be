<?php

namespace Modules\GlobalStatus\Repositories;

use App\Repositories\BaseRepository;
use Modules\GlobalStatus\Entities\GlobalStatus;

class GlobalStatusRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return GlobalStatus::query();
    }

    /**
     * @param array $data
     * @return Collection|GlobalStatus[]
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
