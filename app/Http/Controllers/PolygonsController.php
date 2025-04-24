<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;

class PolygonsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }

     public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate(
            [
                'name' => 'required|unique:polygons,name',
                'description' => 'required',
                'geom_polygon' => 'required', // berdasarkan tabel apa dan kolom apa
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:100',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name is already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry polygon is required',
            ]
        );

        // Create images directory if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
            }

            // Get image file
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
                $image->move('storage/images', $name_image);
            } else {
                $name_image = null;
            }

            $data = [
                'geom' => $request->geom_polygon,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $name_image,
            ];

        // create data
        if (!$this->polygons->create($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to add');
        } // proses untuk memasukkan data nya ke dalam tabel kita

        // redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
