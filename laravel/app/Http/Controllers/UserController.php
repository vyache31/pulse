namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        return User::query()
            ->where('username', 'like', "%{$request->q}%")
            ->limit(10)
            ->get(['id', 'username']);
    }
}