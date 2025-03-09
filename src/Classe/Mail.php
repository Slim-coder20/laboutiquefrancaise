<?php
namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;




class Mail

{

public function send($to_email, $to_name, $subject, $content){
    
    
    $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "slimabida21@gmail.com",
                    'Name' => "La Boutique Française"
                ],
                'To' => [
                    [
                        'Email' => $to_email,
                        'Name' => $to_name
                    ]
                ],
                'templateID' => 6793674,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                'Variables' => [
                    'content' => $content
                ],
               
            ]
        ]
    ];
    
    
    $response = $mj->post(Resources::$Email, ['body' => $body]);


}




}

