<?php
namespace AppView\Repository;

class ProvinceRepository
{
    public function all()
    {
        $result = model('province/index')->load();
        return collect_recursive($result['vars']);
    }
}
