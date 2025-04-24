<?php

namespace App\Models;

use Illuminate\Support\Facades\DB; // menggunakan library DB untuk menjalankan SQL query
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';

    protected $guarded = ['id'];

    public function geojson_points()
    {
        $points = $this
        ->select(DB::raw('st_asgeojson(geom) as geom, name, description, image, created_at, updated_at'))
        ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature', // data list ini akan dimasukkan ke dalam features diatas
                'geometry' => json_decode($p->geom), // decode digunakan agar menjadi sebuah array data dan tdk ditulis dalam bentuk geojson
                'properties' => [
                    'name' => $p->name,
                    'description' => $p->description,
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
