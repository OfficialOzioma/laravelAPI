<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FarhanWazir\GoogleMaps\GMaps;

class MapController extends Controller
{


    public function map()
    {
        $config = [];
        $config['center'] = '5.4891, 7.0176';
        $config['zoom'] = '5';
        $config['map_height'] = '100%';

        $gmap = new GMaps();
        $gmap->initialize($config);

        // $marker = [];
        // $marker['position'] = '5.4207, 7.0767';

        // $marker2 = [];
        // $marker2['position'] = '5.4891, 7.0176';

        // $gmap->add_marker($marker);
        // $gmap->add_marker($marker2);

        // $gmap->add_polyline(
        //     [
        //         'points' => [$marker['position'], $marker2['position']]
        //     ]
        // );


        $map = $gmap->create_map();
        return view('map', compact('map'));
    }
}
