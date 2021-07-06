<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Support\Str;
use App\Http\Resources\SecretResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SecretStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class SecretController extends Controller
{
	public function store(SecretStoreRequest $request) 
	{		
		$newSecret = Secret::create([
			'hash' 		 => Str::uuid(),
			'secretText' => $request->secret,
			'expiresAt'	 => $request->expireAfter != 0 ? date('Y-m-d h:i:sa', strtotime('now + ' . $request->expireAfter . ' min')) : null,
			'remainingViews' => $request->expireAfterViews,
		]);

		return $this->getResponse($request, $newSecret, Response::HTTP_CREATED);
	}

    public function show(Request $request, $hash) 
    {
    	$secret = Secret::findActiveSecretByHash($hash);

    	if ($secret) {
    		$secret->decreaseRemainingViews();

    		return $this->getResponse($request, $secret);
    	}

    	return response()->preferredFormat([
        'message' => 'Secret Not Found'], 404);    	
    }

    public function getResponse($request, $secret, $status = 200)
    {
    	$response = null;

    	switch ($request->header('accept')) {
			case 'application/json':
				$response = response(new SecretResource($secret), $status);
				break;
			case 'application/xml':
				$response = response()->preferredFormat($secret, $status, [], class_basename($secret));
				break;	
			
			default:
				$response = new SecretResource($secret);
				break;
		}

		return $response;
    }
}
