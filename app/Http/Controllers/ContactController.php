<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Contact\IndexContact;
use App\Http\Requests\Contact\StoreContact;
use App\Http\Requests\Contact\UpdateContact;
use App\Http\Requests\Contact\DestroyContact;
use App\Models\Shipper;
use App\Models\Contact;
use App\Repositories\Shippers;
use App\Repositories\Contacts;
use App\Repositories\ApiResponse;
use Inertia\Inertia;


class ContactController extends Controller
{
    private Contacts $repo;
    private Shippers $shipperRepo;
    private ApiResponse $api;

    public function __construct(ApiResponse $apiResponse, Contacts $repo,Shippers $shipperRepo )
    {
        $this->api = $apiResponse;
        $this->repo = $repo;
        $this->shipperRpo = $shipperRepo;
    }

    
    /**
     * Display a listing of the resource (paginated).
     * @return columnsToQuery \Illuminate\Http\JsonResponse
     */
    public function index(IndexContact $request)
    {
        $shipper_id = $request->get('shipper_id');
        $contacts = Contact::latest()->where('shipper_id' , '=' , $shipper_id)->paginate(10);
        return $this->api->success()->message("List of contacts")->payload($contacts)->send();
    }

    
    /**
    * Show the form for creating a new resource.
    *
    * @return  \Inertia\Response
    */
    public function create($shipper_id)
    {
        return Inertia::render("Contacts/Create")->with('shipper_id' , $shipper_id);
    }

     /**
    * Store a newly created resource in storage.
    *
    * @param StoreContact $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StoreContact $request)
    {
        try {
            $data = $request->sanitizedObject();
            $contact = $this->repo::store($data);
            return redirect()->route('shippers.index')->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'New contact Saved Succesfully.',
            ]);;
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with('notification', [
                'color' => 'red',
                'title' => 'Error',
                'message'=> $exception->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Contact $contact)
    {
        try {
            $payload = $this->repo::init($contact)->show($request);
            return $this->api->success()
                        ->message("Contact $contact->id")
                        ->payload($payload)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->send();
        }
    }

     /**
    * Show Edit Form for the specified resource.
    *
    * @param Request $request
    * @param Contact $contact
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request,  $id)
    {
        try {
            //Fetch relationships
            $contact = Contact::where('id',$id)->first();
            return Inertia::render("Contacts/Edit", ["contact" => $contact]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with('notification', [
                'color' => 'red',
                'title' => 'Error',
                'message'=> $exception->getMessage(),
            ]);
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param UpdateContact $request
    * @param {$modelBaseName} $contact
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdateContact $request, $id)
    {
        try {
            
            $contact = Contact::where('id',$id)->first();
            $data = $request->sanitizedObject();
            $res = $this->repo::init($contact)->update($data);
            return redirect()->route('shippers.index')->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'The Contact Was Updated succesfully.',
            ]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with('notification', [
                'color' => 'red',
                'title' => 'Error',
                'message'=> $exception->getMessage(),
            ]);
        }
    }

    
    /**
    * Set Shipper Primary Contact
    *
    * @param Request $request
    * @param {$modelBaseName} $contact_id
    * @return \Illuminate\Http\RedirectResponse
    */
    public function toggle_primary(Request $request , $contact_id){

        try {
            $contact = Contact::where('id',$contact_id)->first();
            $contact->is_primary = !$contact->is_primary;
            $contact->saveOrFail();
            $res = $this->repo::TogglePrimary($contact->shipper->id , $contact_id);
            
            return $this->api->success()->message("The primary Contact Was Updated succesfully.")->send();
            
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->send();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyContact $request,  $id)
    {
        
        $contact = Contact::where('id',$id)->first();
        $res = $this->repo::init($contact)->destroy();
        if ($res) {
            return back()
            ->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'The Contact was deleted succesfully.',
            ]);
        }
        else {
            return back()->with('notification', [
                'color' => 'red',
                'title' => 'Error',
                'message'=> 'The Contact could not be deleted.',
            ]);
        }
    }
}
