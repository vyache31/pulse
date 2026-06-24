<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
	{
    	$query = $request->get('q');
    	if (strlen($query) < 2) {
        	return response()->json([]);
    	}

    	$workspaceId = $request->get('workspace_id');
    	$users = User::where('username', 'LIKE', "%{$query}%")
        	->when($workspaceId, function ($q) use ($workspaceId) {
            	$q->whereDoesntHave('workspaceMembers', function ($sub) use ($workspaceId) {
                	$sub->where('workspace_id', $workspaceId);
            	});
        	})
        	->limit(10)
        	->get(['id', 'username']);

    	return response()->json($users);
	}
}