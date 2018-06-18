<?php

	$loginUrl = "https://login.microsoftonline.com/:tenant_id/oauth2/token";

	$tenant_id 		= 	"c527ef60-0449-4833-ac4e-bf9e517e16a3";
	$grant_type 	=	"client_credentials";
	$client_id 		= 	"e23bec10-c7f8-4a17-b54c-66d8ac50a1ba";
	$client_secret	= 	"oizkj+crCSJ8BpIqKSBHEAHMfvRD2+2yi9cfk3COofk=";
	$resource  		=	"https://management.azure.com/";

	$postData = array(
		"grant_type" 	=> $grant_type,
		"client_id"		=> $client_id,
		"client_secret"	=> $client_secret,
		"resource"		=> $resource,
	);


	function request($url, $data){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true); // use POST method
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // send output to a variable for processing
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // set to false if you want to see what redirect header was sent
		$output = curl_exec($curl);

		if ($output === false)
		  {
		    //if the request doesn't send 
		    echo 'Curl error: ' . curl_error($curl). '<br />';
		    echo 'Curl info: ' . curl_getinfo($curl). '<br />';
		    curl_close($curl);
		    return json_encode(array("error"=>"error"));
		  }
		  //Success
		  else
		  {
		    //Close connection		 
		    curl_close($curl);
		    return json_decode($output);
		}
	}

// get AAD token
	$access = request(str_replace(":tenant_id", $tenant_id, $loginUrl), $postData);
	$accessToken = $access->access_token;
	var_dump($access);	

// test api endpoint "https://jadeapi.azurewebsites.net/api/PipeDetails"
	$header = "Authorization: Bearer " . $accessToken;
	$url = "https://jadeapi.azurewebsites.net/api/PipeDetails";

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // send output to a variable for processing
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // set to false if you want to see what redirect header was sent=
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		$header,  
		'Accept:application/json;odata=minimalmetadata',
        'Content-Type:application/json;odata=minimalmetadata', 
        'Prefer:return-content')
	);

	$output = curl_exec($curl);
	var_dump($output);
	if ($output === false)
	  {
	    //if the request doesn't send 
	    echo 'Curl error: ' . curl_error($curl). '<br />';
	    echo 'Curl info: ' . curl_getinfo($curl). '<br />';
	    curl_close($curl);

	    // return json_encode(array("error"=>"error"));
	  }
	  //Success
	  else
	  {
	    //Close connection		 
	    curl_close($curl);
	    echo "<pre>";
	    print_r(json_decode($output));
	    echo "</pre>";
	}

	


