<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        $polylines = $this->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image,
        st_length(geom, true) as length_m, st_length(geom, true)/1000 as length_km, created_at, updated_at'))
        ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $p) {
            $feature = [
                'type' => 'Feature', // data list ini akan dimasukkan ke dalam features diatas
                'geometry' => json_decode($p->geom), // decode digunakan agar menjadi sebuah array data dan tdk ditulis dalam bentuk geojson
                'properties' => [
                    'name' => $p->name,
                    'description' => $p->description,
                    'length_m' => $p->length_m,
                    'length_km' => $p->length_km,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
