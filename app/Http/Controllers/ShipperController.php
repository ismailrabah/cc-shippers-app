<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Shipper\IndexShipper;
use App\Http\Requests\Shipper\StoreShipper;
use App\Http\Requests\Shipper\UpdateShipper;
use App\Http\Requests\Shipper\DestroyShipper;
use App\Models\Shipper;
use App\Repositories\Shippers;
use Inertia\Inertia;


class ShipperController extends Controller
{
    private Shippers $repo;
    public function __construct(Shippers $repo )
    {
        $this->repo = $repo;
    }

    /**
    * Display a listing of the resource.
    *
    * @param  Request $request
    * @return    \Inertia\Response
    */
    public function index(IndexShipper $request): \Inertia\Response
    {
        $shippers = Shipper::latest()->paginate(10);
        
        return Inertia::render('Shippers/index',[
            'shippers' => $shippers,
            'search' => ""
         ]);
    }

    
    /**
    * Display a listing of the resource.
    *
    * @param  Request $item
    * @return    \json
    */
    public function search(Request $item)
    {
        $data = Shipper::where("company_name",'LIKE','%'.$item.'%')->paginate(10);
        return ['data' => $data, 'search' => $item];
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return  \Inertia\Response
    */
    public function create()
    {
        return Inertia::render("Shippers/Create");
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param StoreShipper $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StoreShipper $request)
    {
        try {
            $data = $request->sanitizedObject();
            $shipper = $this->repo::store($data);
            return redirect()->route('shippers.index')->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'New Shipper Saved Succesfully.',
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
    * Display the specified resource.
    *
    * @param Request $request
    * @param Shipper $shipper
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function show(Request $request, Shipper $shipper)
    {
        try {
            $model = $this->repo::init($shipper)->show($request);
            return Inertia::render("Shippers/Show", ["model" => $model]);
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
    * Show Edit Form for the specified resource.
    *
    * @param Request $request
    * @param Shipper $shipper
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request,  $id)
    {
        try {
            //Fetch relationships
            $shipper = Shipper::where('id',$id)->first();
            return Inertia::render("Shippers/Edit", ["shipper" => $shipper]);
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
    * @param UpdateShipper $request
    * @param {$modelBaseName} $shipper
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdateShipper $request, $id)
    {
        try {
            
            $shipper = Shipper::where('id',$id)->first();
            $data = $request->sanitizedObject();
            $res = $this->repo::init($shipper)->update($data);
            return redirect()->route('shippers.index')->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'The Shipper Was Updated succesfully.',
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
    * Remove the specified resource from storage.
    *
    * @param Shipper $shipper
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(DestroyShipper $request, $id)
    {
        $shipper = Shipper::where('id',$id)->first();
        $res = $this->repo::init($shipper)->destroy();
        if ($res) {
            return back()->with('notification', [
                'color' => 'green',
                'title' => 'success',
                'message'=> 'The Shipper was deleted succesfully.',
            ]);
        }
        else {
            return back()->with('notification', [
                'color' => 'red',
                'title' => 'Error',
                'message'=> 'The Shipper could not be deleted.',
            ]);
        }
    }
}
