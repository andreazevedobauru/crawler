<?php

namespace App\Http\Controllers;

use App\Models\Vagas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Weidner\Goutte\GoutteFacade;

class GetAnswer extends Controller
{
    private $url = 'http://applicant-test.us-east-1.elasticbeanstalk.com';

    private $replacements = [
        'a' =>'z','b' =>'y','c' =>'x','d' =>'w','e' =>'v','f' =>'u','g' =>'t',
        'h' =>'s','i' =>'r','j' =>'q','k' =>'p','l' =>'o','m' =>'n','n' =>'m',
        'o' =>'l','p' =>'k','q' =>'j','r' =>'i','s' =>'h','t' =>'g','u' =>'f',
        'v' =>'e','w' =>'d','x' =>'c','y' =>'b','z' =>'a','0' =>'9','1' =>'8',
        '2' =>'7','3' =>'6','4' =>'5','5' =>'4','6' =>'3','7' =>'2','8' =>'1',
        '9' =>'0'
    ];

    private $resposta;

    public function getForm(Request $request) {
        $crawler = GoutteFacade::request('GET', $this->url);
        // Pega o token que é gerado toda vez que entra no formulário
        $crawler->filterXpath('//input')->each(function ($node) {
            $this->resposta = $this->getAnswer($this->revertToken($node->attr('value')));
        });
        return response()->json(["data" => $this->resposta]);
    }

    public function getFormWithCurl()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        preg_match_all("/<input type=\"hidden\"(.*)id=\"token\" value=\"(.*?)\"(.*)>/", $result, $matches);
        return response()->json(["data" => $this->getAnswerWithCurl($this->revertToken($matches[2][0]), $cookies['PHPSESSID'])]);
    }

    public function getAnswerWithCurl($token, $cookie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"token\": \"$token\"}");
        $headers = array();
        $headers[] = "Cookie: PHPSESSID=$cookie";
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        return curl_exec($ch);
    }

    public function getAnswer($token) {
        $response = Http::withHeaders([
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding' => 'gzip, deflate',
                'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7,gl;q=0.6',
                'Cache-Control'=> 'max-age=0',
                'Connection'=> 'keep-alive',
                'Upgrade-Insecure-Requests'=> '1',
                'Content-Length' => '38',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Host' => 'applicant-test.us-east-1.elasticbeanstalk.com',
                'Origin' => 'http://applicant-test.us-east-1.elasticbeanstalk.com',
                'Referer' => 'http://applicant-test.us-east-1.elasticbeanstalk.com/',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
        ])->post(
            $this->url,
            [
                'token' => $token,
            ]
        );
        return $response->body();
    }

    public function revertToken($token)
    {
        $finalToken = '';
        for($e = str_split($token), $t=0; $t < count($e); $t++){
            $e[$t] = (isset($this->replacements[$e[$t]])) ? $this->replacements[$e[$t]] : $e[$t];
            $finalToken .= $e[$t];
        }

        return $finalToken;
    }
}
