<?php
class LineLogin
{
    // Change your Line Channel ID, Channel Secret, and Redirect URL
    private const CLIENT_ID = '2000097154';
    private const CLIENT_SECRET = 'dd5035e91b8b6f436201bacf0aef6431';
    private const REDIRECT_URL = 'http://localhost/adminlte3/callback.php';

    private const AUTH_URL = 'https://access.line.me/oauth2/v2.1/authorize';
    private const PROFILE_URL = 'https://api.line.me/v2/profile';
    private const TOKEN_URL = 'https://api.line.me/oauth2/v2.1/token';
    private const REVOKE_URL = 'https://api.line.me/oauth2/v2.1/revoke';
    private const VERIFYTOKEN_URL = 'https://api.line.me/oauth2/v2.1/verify';

    function getLink()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);

        $link = self::AUTH_URL . '?response_type=code&client_id=' . self::CLIENT_ID . '&redirect_uri=' . self::REDIRECT_URL . '&scope=profile%20openid%20email&state=' . $_SESSION['state'];
        return $link;
    }

    function token($code, $state)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['state'] != $state) {
            return false;
        }

        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $data = [
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => self::REDIRECT_URL,
            "client_id" => self::CLIENT_ID,
            "client_secret" => self::CLIENT_SECRET
        ];

        $response = $this->sendCURL(self::TOKEN_URL, $header, 'POST', $data);
        return json_decode($response);
    }

    function checkUserExistsInDatabase($profile)
    {
        require_once 'config/database.php';
        // Connect to your database
        $objCon = connectDB();

        // Escape and sanitize the user data
        $email = mysqli_real_escape_string($objCon, $profile->email);

        // Check if the user exists in the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($objCon, $query);
        $row = mysqli_num_rows($result);

        // Close the database connection
        mysqli_close($objCon);

        return $row > 0;
    }

    function profileFormIdToken($token = null)
    {
        $payload = explode('.', $token->id_token);
        $ret = array(
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'name' => '',
            'picture' => '',
            'email' => ''
        );

        if (count($payload) == 3) {
            $data = json_decode(base64_decode($payload[1]));
            if (isset($data->name))
                $ret['name'] = $data->name;

            if (isset($data->picture))
                $ret['picture'] = $data->picture;

            if (isset($data->email))
                $ret['email'] = $data->email;
        }
        return (object) $ret;
    }

    function saveUserToDatabase($profile)
{
    // Connect to your database
    $objCon = connectDB();

    // Escape and sanitize the user data
    $name = mysqli_real_escape_string($objCon, $profile->name);
    $picture = mysqli_real_escape_string($objCon, $profile->picture);
    $email = mysqli_real_escape_string($objCon, $profile->email);

    // Check if the user already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($objCon, $query);

    if (mysqli_num_rows($result) > 0) {
        // User already exists, update the user's data
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $updateQuery = "UPDATE users SET name = '$name', picture = '$picture' WHERE user_id = '$user_id'";
        mysqli_query($objCon, $updateQuery);
    } else {
        // User doesn't exist, create a new user
        $insertQuery = "INSERT INTO users (full_name, picture, email,user_type,username,password,phone_number,address) VALUES ('$name', '$picture', '$email','user','','','','')";
        mysqli_query($objCon, $insertQuery);
    }

    // Close the database connection
    mysqli_close($objCon);

    // Return the saved name
    return $name;
}


    private function sendCURL($url, $header, $type, $data = NULL)
    {
        $request = curl_init();

        if ($header != NULL) {
            curl_setopt($request, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);

        if (strtoupper($type) === 'POST') {
            curl_setopt($request, CURLOPT_POST, TRUE);
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($request, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($request);
        return $response;
    }
}
