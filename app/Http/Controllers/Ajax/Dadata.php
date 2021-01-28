<?php


namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\Controller;
use App\Models\Helper\Region;
use Illuminate\Http\Request;

class Dadata extends Controller
{
    public $url = '';
    public $headers = [
        "Content-type: application/json",
        "Accept: application/json",
        "Authorization: Token ccd4a5e4c462095321ff5b6068cea3a6e175e9ce"
    ];

    /**
     * @return mixed
     */
    public function async()
    {
        return call_user_func_array([__CLASS__, request()->get('func')], [request()->all()]);
    }

    /**
     * @return bool|string
     */
    public function curlInit()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU");

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "query": "' . \request()->get('query') . '", "locations_boost": [{"kladr_id": "77"}], "count": 5 }');

        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            return "Error: " . curl_error($ch);
        } else {
            curl_close($ch);
        }
        return $data;
    }

    /**
     * @return bool|string
     */
    public function address()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
        return $this->curlInit();
    }

    /**
     * @return bool|string
     */
    public function inn()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party";
        return $this->curlInit();
    }

    /**
     * @return bool|string
     */
    public function organization()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/party";
        return $this->curlInit();
    }

    /**
     * @return bool|string
     */
    public function bank()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/bank";
        return $this->curlInit();
    }

    /**
     * @return bool|string
     */
    public function fio()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/fio";
        return $this->curlInit();
    }

    /**
     * @return bool|string
     */
    public function email()
    {
        $this->url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/email";
        return $this->curlInit();
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function osm()
    {
        $flat = \request()->get('flat');
        $flon = \request()->get('flon');

       $tlat = 55.875310835696816;
        $tlon = 37.69958496093751;
/*
        $region = $this->getRegion(\request()->get('region'));

        if ($region == 'not-available') {
            return 'not-available';
        } elseif ($region->center_lat) {
            $tlat = $region->center_lat;
            $tlon = $region->center_lon;
        } elseif (\request()->get('lat')) {
            $tlat = \request()->get('lat');
            $tlon = \request()->get('lon');
        } else {
            $tlat = 55.875310835696816;
            $tlon = 37.69958496093751;
        }*/
        $u = 'http://www.yournavigation.org/api/1.0/gosmore.php?flat=' . $flat . '&flon=' . $flon . '&tlat=' . $tlat . '&tlon=' . $tlon . '&format=geojson';

        return file_get_contents($u);
    }

    public function getRegion($reg)
    {
        if ($reg) {
            $region = Region::whereRegion($reg)->first();
            if ($region) {
                return $region;
            } else {
                return 'not-available';
            }
        } else {
            return false;
        }

    }

}
