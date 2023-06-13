<?php

    function instagram($username)
    {
        if (!$username) {
            return false;
        }

        $url = "https://i.instagram.com/api/v1/users/web_profile_info/?username=".$username;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER => [
                'User-Agent: Instagram 76.0.0.15.395 Android (24/7.0; 640dpi; 1440x2560; samsung; SM-G930F; herolte; samsungexynos8890; en_US; 138226743)',
            ]
        ]);

        $result     = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function save_followers_count($followers_count)
    {
        $date       = date('Y-m-d');
        $filename   = 'total.txt';

        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            $previousCount = intval($data);
        } else {
            $previousCount = 0;
        }

        file_put_contents($filename, $followers_count);
        $difference = $followers_count - $previousCount;
        $increase   = $previousCount - $followers_count;

        echo "Tanggal: $date \n \n"."<br>";
        echo "Jumlah Pengikut Saat Ini: $followers_count \n \n <br>";
        echo "Perbedaan Dibandingkan Sebelumnya: $difference \n \n<br>";
        echo "Bertambah sejumlah: $increase <br>";
    }

    if (!isset($_GET['username'])) {
        echo 'Silahkan masukkan username terlebih dahulu';
        return;
    }

    $username = $_GET['username'];
    echo 'Mengambil data instagram untuk: '.$username.' <br>';
    echo '-------------------------------------------------- <br>';
    $result     = instagram($username);
    $response   = json_decode($result);
    $followers  = $response->data->user->edge_followed_by->count;
    save_followers_count($followers);
?>