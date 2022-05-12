<?php
namespace App\Repositories;

use App\Models\Shipper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class Shippers
{
    private Shipper $model;
    public static function init(Shipper $model): Shippers
    {
        $instance = new self;
        $instance->model = $model;
        return $instance;
    }

    public static function store(object $data): Shipper
    {
        $model = new Shipper((array) $data);
        // Save Relationships
                    
        $model->saveOrFail();
        return $model;
    }

    public function show(Request $request): Shipper {
        //Fetch relationships
         //Fetch relationships
         $this->model->load([
            'contacts'
        ]);
        return $this->model;
    }

    public function update(object $data): Shipper
    {
        $this->model->update((array) $data);
        // Save Relationships


        $this->model->saveOrFail();
        return $this->model;
    }

    public function destroy(): bool
    {
        return !!$this->model->delete();
    }

  
}
