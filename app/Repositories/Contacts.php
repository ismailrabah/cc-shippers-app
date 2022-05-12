<?php
namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class Contacts
{
    private Contact $model;
    public static function init(Contact $model): Contacts
    {
        $instance = new self;
        $instance->model = $model;
        return $instance;
    }

    public static function store(object $data): Contact
    {
        $model = new Contact((array) $data);
        // Save Relationships
        
        if (isset($data->shipper_id)) {
            $model->shipper()->associate($data->shipper_id);
        }
                    
        $model->saveOrFail();

        if($model->is_primary)
            self::TogglePrimary($model->shipper->id , $model->id);

        return $model;
    }

    public function show(Request $request): Contact {
        //Fetch relationships
        $this->model->load([
            'shipper'
        ]);
        return $this->model;
    }

    public function update(object $data): Contact
    {
        $this->model->update((array) $data);
        // Save Relationships
        
        if (isset($data->shipper_id)) {
            $this->model->shipper()->associate($data->shipper_id);
        }
        
        $this->model->saveOrFail();

        if($this->model->is_primary)
            self::TogglePrimary($this->model->shipper->id , $this->model->id);

        return $this->model;
    }

    public function destroy(): bool
    {
        return !!$this->model->delete();
    }

    public static function TogglePrimary($shipper_id , $contact_id){
        $old_pri = Contact::where('shipper_id' , '=' , $shipper_id)
            ->where('is_primary' , '=' , 1)
            ->where('id' , '<>' , $contact_id)
            ->first();
        if($old_pri){
            $old_pri->is_primary = false;
            $old_pri->saveOrFail();
        }
    } 

  
}
