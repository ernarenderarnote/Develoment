<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application splash screen.
     *
     * @return Response
     */
    public function show()
    {
        return view('index');
    }

    public function gmail(){
    	$to = "narender.techsparksit@gmail.com";
		$subject = "My subject";
		$txt = "Hello world!";
		$headers = "From: webmaster@example.com" . "\r\n";

		mail($to,$subject,$txt,$headers);
		$file = fopen("test.txt","w");
		echo fwrite($file,"Hello World. Testing!");
		fclose($file);
    }
}
