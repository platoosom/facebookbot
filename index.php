
<?php

$access_token = 'EAAFUz3HsNTEBACQcoC5nzuLMKxDYfaAzdnbEX3pThnbzQZAjQSDDzzoZC7FLGmk0ZAO3wm6a90MiDVZB3a81zrqaFakTBmK2ZCxsySoQTZA7ZBeCBemeWcV5hFtLDKLdY5SN3ZBVNvxli0g28zCidUf82Jine21D76vKrlCAuNthqAZDZD';

/* validate verify token needed for setting up web hook */ 

if (isset($_GET['hub_verify_token'])) { 
    if ($_GET['hub_verify_token'] === $access_token) {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}

/* receive and send messages */
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {
    
    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
    $text_from_user = $input['entry'][0]['messaging'][0]['message']['text'];

    // Get question list
    $questions = array(
        '1' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-1-3679.jpg',
            'text' => '2 พยางค์',
            'answer' => 'อิดโรย',
        ),
        '2' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-2-3890.jpg',
            'text' => '7 พยางค์',
            'answer' => 'เด็กดูได้ผู้ใหญ่ดูดี',
        ),
        '3' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-3-9806.jpg',
            'text' => '3 พยางค์',
            'answer' => 'สังกะสี',
        ),
        '4' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-4-3521.gif',
            'text' => '3 พยางค์',
            'answer' => 'เหล็กกล้า',
        ),
        '5' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-5-1129.jpg',
            'text' => '3 พยางค์',
            'answer' => 'วงดนตรี',
        ),
        '6' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-6-2769.jpg',
            'text' => '3 พยางค์',
            'answer' => 'ขนมจีบ',
        ),
        '7' => array(
            'image' => 'https://scontent-fbkk5-7.us-fbcdn.net/v1/t.1-48/1426l78O9684I4108ZPH0J4S8_842023153_K1DlXQOI5DHP/dskvvc.qpjhg.xmwo/p/data/127/127071-7-8455.jpg',
            'text' => '3 พยางค์',
            'answer' => 'บังกะโล',
        ),
    );

    // Find string pattern.
    if(strpos('answer:', $text_from_user) !== false) {
        
        // Check answer
        list($keyword, $answer) = explode(':', $text_from_user);
        
        // Get current question.
        $question = $questions[$_SESSION[$sender]['current_question']];

        // If true go next, If false stop
        if($answer == $question['answer']){
        
                /*prepare response*/
                $resp     = array(
                    'messaging_type' => 'RESPONSE',
                    'recipient' => array(
                        'id' => $sender
                    ),
                    'message' => array(
                        'text' => $answer. ' is right!!. Good',
                    ),
                );
                $jsonData = json_encode($resp);

        } else {
        
                /*prepare response*/
                $resp     = array(
                    'messaging_type' => 'RESPONSE',
                    'recipient' => array(
                        'id' => $sender
                    ),
                    'message' => array(
                        'text' => $answer. ' is wrong. Please answer again.',
                    ),
                );
                $jsonData = json_encode($resp);

        }

        // Facebook API endpoint.
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='. $access_token;
        
        /*initialize curl*/
        $ch = curl_init($url);

        /* curl setting to send a json post data */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch); // user will get the message
        
    }else{
        session_start();

        // Random question of game.
        $index = array_rand($questions);
        $question = $questions[$index];
        $_SESSION[$sender]['current_question'] = $index;

        // Facebook API endpoint.
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='. $access_token;
        
        /*initialize curl*/
        $ch = curl_init($url);
        
        /*prepare response*/
        $resp     = array(
            'messaging_type' => 'RESPONSE',
            'recipient' => array(
                'id' => $sender
            ),
            'message' => array(
                'attachment' => array(
                    'type' => 'image',
                    'payload' => array(
                        'url' => $question['image'],
                        'is_reusable' => false
                    )
                ),
            ),
        );
        $jsonData = json_encode($resp);

        /* curl setting to send a json post data */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch); // user will get the message

        /*prepare response*/
        $resp     = array(
            'messaging_type' => 'RESPONSE',
            'recipient' => array(
                'id' => $sender
            ),
            'message' => array(
                'text' => $question['text'] .' '. 'คุณสามารถตอบคำถามเกมส์ในรูปแบบ answer:xxx',
            ),
        );
        $jsonData = json_encode($resp);

        /* curl setting to send a json post data */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch); // user will get the message

    }

} 
