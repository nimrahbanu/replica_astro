<?php

namespace App\Services;
use GuzzleHttp\Client;

class FCMService
{


     // Public property to store the service account key
     public $serviceAccountKey = [
            "type"=>"service_account",
            "project_id"=> "astrowildstar-c0259",
            "private_key_id"=> "4a8a1037487a4379e6de48c342bdf10b63264782",
            "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCeFuEMRiiHksfE\nFvKsD8+c5VFuxT23Mg1SHAGvltBCNGRvVATIOL6INlIqjdUW6UwDAlniNAeR7UTD\nbKx7reNknlVfe5pSHiQYJnFE+DoIclFoh0nMFve30a2S50jmQ3BVpjo2Yfza+Sgl\nyMEK7l8KA3bN3Pavg2E7Is7871r8rGJcox++VrqtXooGg6EhiUgP6849KnEtK8Yq\nvnJZVJCdRfZDyIshXVzETg+0R3cFemhZ0DZyuXpZRtNCXq4j6FmXi+x9ebd1l68L\nppM3feJLAJCoHRhwEHxorGm0SiAhOM2o2O44utQsyz4rEf7bqzlZ8cCTAxufSPdA\nB2A+JjktAgMBAAECggEABCr76vFslHUeYYFDYPLtGSit7BrfeirOfBmIl9haNuyf\nsJReqJK1FJfuCaOB+Ip/ul0/q72NwjSnCUnzsDJmRNsByt8niJO1MD0kjlocWZL9\n/3YAUD+oxJwOv0lBZu9kkPWCzQ0/TTH1c4LmnVhUgXSUQhrZwXMtcebZ3dmc+f1c\n4ZUXZctxhPNiZInEMmNz7B9wdsxRinw4usVVMZ0N6DAVB6ZcK7yXjeUcDtWKSJ6g\nggSRsguBy+EGaSVExhB0d5hB9r7DYsF6QWj5a5/4kFLhnbXiUC2iMmt3HV/Vj7ap\nOr6j/Walpj04HUD4yYsIxTTi6p7PnGILQqYfvUQjNwKBgQDNvIVRxy+sY6TlmOWu\nOC1pR7alqy5YSf/51Nexh6AuusnFs4+8hOOoRgcxVCFD+i4RfWij4ariiXhZZJ3/\nmc9yatfanRy0yD3vj+DuoMEwF2T85Q7NdQ64IK/autYznm0lKX/oMSEH1Cej4Hk1\nz/Eqhzx8M5c/AaYFKWX8xPpz1wKBgQDEtlhIRAXlHVC95h+hWZHapaHY8niA4IoS\nXj0Wdwq1aZYPqWBeSi5+g8JeeGibrCqdCzZmfwAlX4afq/fcDNEWxtuYh+0UO0km\n8TTD4sKxhBFstSDokRQh9hpPMIcFixWZhbaaTzePH2TNtS/bBUiRhBv/3w3kfTDQ\njOfn32vamwKBgEkU3obeKqEiBEFKvsvUSM1NHCRdWmkiYDtuz+/QLaZr06DW2Agv\nbG8p8QQkzmxHQnYUBkewsfMmwgl/JGDXUkliiqqthLTzLI0cntolYHqk1MrA0zFI\nk6H0eoNIOy666Cp7Q2RHj2QoiKw94NCsvQ5OW74C1YccHs1Wl1Pi5NShAoGBAI1T\nI+HnXQTaJuQHrLnTDAK19K1UplaQ+yFvKxw0sjDbhsABxAZ57SVfrAkLILyW2Jaz\nM4Y3v/cZxjJ47j3dx2pBvAq9vQpH/apIqMwC4jV+2LXs+5Oah4hOs2ApURgecIo3\nkpUSTKw6tcVEYvlLtnM1IpiVu/loJ+XtFb90uJv5AoGADXGXorvRVwi/rJa5F2Y+\n3GH5sLDy+WTKZ5+3n7GSJiqijgC31/9M2Dby+mv8X6NBIvnebMb+rMVSiFx93L4X\ndJXHdzI0OOtcese4XNfqCWo6rh9HAnx8e9jxWkl3JOFdepQXp9QD7BH7+JdTr8Hf\nozIcFqKv6hUEzSvL4z7Ggyc=\n-----END PRIVATE KEY-----\n",
            "client_email"=> "firebase-adminsdk-o1lrf@astrowildstar-c0259.iam.gserviceaccount.com",
            "client_id"=> "104695383978135704141",
            "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
            "token_uri"=> "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-o1lrf%40astrowildstar-c0259.iam.gserviceaccount.com",
            "universe_domain"=> "googleapis.com"
    ];


public static function send($userDeviceDetail, $notification)
{
    $fcmService = new self();
    $projectId = env('FCM_PROJECT_ID');
    $serverApiKey = env('FCM_SERVER_KEY');
    $accessToken = $fcmService->getAccessToken($serverApiKey);

    $endpoint = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';

    $responses = []; // Array to store individual responses

    foreach ($userDeviceDetail->pluck('fcmToken')->all() as $token) {
        $notificationType = isset($notification['body']['notificationType']) ? (string) $notification['body']['notificationType'] : null;


        // $payload = [
        //     'message' => [
        //         'token' => $token,
        //         'notification' => [
        //             'title' => $notification['title'],
        //             'body' => $notification['body']['description'],
        //         ],
        //         'data' => [
        //             'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        //             'body' => json_encode($notification['body']),

        //         ],
        //         'android' => [
        //             'priority' => 'high',
        //         ],
        //     ],
        // ];

        $payload = [
            'message' => [
                'token' => $token,
                // 'notification' => [
                //     'title' => $notification['title'],
                //     'body' => $notification['body']['description'],
                // ],
                'data' => [
                    'title' => $notification['title'],
                     'description' => $notification['body']['description'],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'body' => json_encode($notification['body']),

                ],
                'android' => [
                    'priority' => 'high',
                ],
            ],
        ];


        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $responses[] = json_decode($response, true);
    }

    return $responses;
}


    private function getAccessToken($serverApiKey)
    {
        $url = 'https://www.googleapis.com/oauth2/v4/token';
        $data = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->generateJwtAssertion($serverApiKey),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $body = json_decode($response, true);

        return $body['access_token'];
    }


    private function generateJwtAssertion($serverApiKey)
{
    $now = time();
    $exp = $now + 3600; // Token expires in 1 hour

    $jwtClaims = [
        'iss' => $this->serviceAccountKey['client_email'],
        'sub' => $this->serviceAccountKey['client_email'],
        'aud' => 'https://www.googleapis.com/oauth2/v4/token',
        'scope' => 'https://www.googleapis.com/auth/cloud-platform',
        'iat' => $now,
        'exp' => $exp,
    ];

    $jwtHeader = [
        'alg' => 'RS256',
        'typ' => 'JWT',
    ];

    $base64UrlEncodedHeader = $this->base64UrlEncode(json_encode($jwtHeader));
    $base64UrlEncodedClaims = $this->base64UrlEncode(json_encode($jwtClaims));

    $signatureInput = $base64UrlEncodedHeader.'.'.$base64UrlEncodedClaims;

    $privateKey = openssl_pkey_get_private($this->serviceAccountKey['private_key']);
    openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    openssl_free_key($privateKey);

    $base64UrlEncodedSignature = $this->base64UrlEncode($signature);

    return $signatureInput.'.'.$base64UrlEncodedSignature;
}



    private function base64UrlEncode($input)
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }



    // public static function send($userDeviceDetail, $notification)
    // {
    //     $serverApiKey = env('FCM_SERVER_KEY');
    //     $payload = [
    //         "notification" => [
    //             "title" => $notification['title'],
    //             "body" => $notification['body']['description'],
    //         ],
    //         "data" => [
    //             "click_action" => "FLUTTER_NOTIFICATION_CLICK",
    //             "body" => $notification['body'],

    //         ],
    //         "android" => [
    //             "priority" => 'high',
    //         ],
    //         "registration_ids" => $userDeviceDetail->pluck('fcmToken')->all(),
    //     ];
    //     $dataString = json_encode($payload);
    //     $headers = [
    //         'Authorization: key=' . $serverApiKey,
    //         'Content-Type: application/json',
    //     ];
    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //     return curl_exec($ch);

	// 	curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	// 	curl_setopt($ch, CURLOPT_POST, true);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
	// 	// Set a short timeout to make the request asynchronous
	// 	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	// 	 // Execute the request in the background
	// 	curl_exec($ch);
	// 	// Close the cURL handle
	// 	curl_close($ch);
	// 	return true;
    // }
}
