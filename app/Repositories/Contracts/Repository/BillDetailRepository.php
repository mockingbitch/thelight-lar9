<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\BillDetail;
use App\Repositories\Contracts\Interface\BillDetailRepositoryInterface;
use App\Repositories\BaseRepository;

class BillDetailRepository extends BaseRepository implements BillDetailRepositoryInterface
{
    public function getModel()
    {
        return BillDetail::class;
    }
}