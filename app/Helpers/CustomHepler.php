<?php
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
if (!function_exists('createVoiceFromFPT')) {
    function createVoiceFromFPT($text)
    {
        $client = new Client();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fpt.ai/hmi/tts/v5',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $text,
            CURLOPT_HTTPHEADER => array(
                'api-key: riQD8kNeNXYGCZz13aoOq1ycK1UeAtmf',
                'speed: -1.5',
                'voice: banmai',
            ),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
            return null;
        }
        $info = curl_getinfo($curl);
        $decoded_response = json_decode($response, true);
        return $decoded_response["async"];
    }
}
if (!function_exists('downloadVoiceFromURL')) {
    function downloadVoiceFromURL($url, $path, $fileName)
    {
        try{
            $contents = file_get_contents($url);
            if (!is_dir(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }
            file_put_contents(public_path($path . $fileName), $contents);
        }catch(\Exception $exception){
            downloadVoiceFromURL($url, $path, $fileName);
        }
    }
}
if (!function_exists('returnJSON')) {
    function returnJSON($data)
    {
       return response()->json($data);
    }
}
if (!function_exists('formatregistration')) {
    function formatregistration($data)
    {
        return $data < 10 ? "00$data" : ($data < 100 ? "0$data" : $data);
    }
}
if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $carbonCreatedAt = Carbon::parse($date);
        return $carbonCreatedAt->format('d/m/Y');
    }
}
if (!function_exists('number_to_word')) {
    function number_to_word($number)
        {
            $hyphen = ' ';
            $conjunction = '  ';
            $separator = ' ';
            $negative = 'âm ';
            $decimal = ' phẩy ';
            $dictionary = array(
                0 => 'Không',
                1 => 'Một',
                2 => 'Hai',
                3 => 'Ba',
                4 => 'Bốn',
                5 => 'Năm',
                6 => 'Sáu',
                7 => 'Bảy',
                8 => 'Tám',
                9 => 'Chín',
                10 => 'Mười',
                11 => 'Mười một',
                12 => 'Mười hai',
                13 => 'Mười ba',
                14 => 'Mười bốn',
                15 => 'Mười lăm',
                16 => 'Mười sáu',
                17 => 'Mười bảy',
                18 => 'Mười tám',
                19 => 'Mười chín',
                20 => 'Hai mươi',
                30 => 'Ba mươi',
                40 => 'Bốn mươi',
                50 => 'Năm mươi',
                60 => 'Sáu mươi',
                70 => 'Bảy mươi',
                80 => 'Tám mươi',
                90 => 'Chín mươi',
                100 => 'trăm',
                1000 => 'nghìn',
                1000000 => 'triệu',
                1000000000 => 'tỷ',
                1000000000000 => 'nghìn tỷ',
                1000000000000000 => 'nghìn triệu triệu',
                1000000000000000000 => 'tỷ tỷ'
            );

            if( !is_numeric( $number ) )
            {
                return false;
            }

            if( ($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX )
            {
                trigger_error( 'number_to_word only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING );
                return false;
            }

            if ($number < 0) {
                return $negative . number_to_word(abs($number));
            }

            $string = $fraction = null;

            if( strpos( $number, '.' ) !== false )
            {
                list( $number, $fraction ) = explode( '.', $number );
            }

            switch (true)
            {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens = ((int)($number / 10)) * 10;
                    $units = $number % 10;
                    $string = $dictionary[$tens];
                    if( $units )
                    {
                        $string .= $hyphen . $dictionary[$units];
                    }
                    break;
                case $number < 1000:
                    $hundreds = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction . number_to_word($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow( 1000, floor( log( $number, 1000 ) ) );
                    $numBaseUnits = (int)($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = number_to_word($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if( $remainder )
                    {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= $conjunction . number_to_word($remainder);
                    }
                    break;
            }

            if( null !== $fraction && is_numeric( $fraction ) )
            {
                $string .= $decimal;
                $words = array( );
                foreach( str_split((string) $fraction) as $number )
                {
                    $words[] = $dictionary[$number];
                }
                $string .= implode( ' ', $words );
            }

            return $string;
        }
}