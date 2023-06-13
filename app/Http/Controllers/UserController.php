<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeAvatar(Request $request, User $user) {
        $request->validate([
            'avatar'=>'required|image|max:3000']);
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg'); //returns the image resized and encoded to jpeg
        $user = auth()->user();
        $fileName = $user->id.'-'.uniqid().'.jpg';// creating a uniuqe file name for the image
        Storage::put("public/avatars/{$fileName}", $imgData);//Storing the actual image
        //grabbing the oldvalue of the avatar field
        $oldFileName = $user->avatar;
        $user->avatar = $fileName;
        $user->save();
        //we can now delete the old file from our server harddrive
        $oldFileName = str_replace('/storage/','public/',$oldFileName);
        Storage::delete(str_replace('/fallback/','avatar/',$oldFileName));
        return back()->with('success', 'avatar uploaded successfully');
    }

    public function showAvatarForm() {
        return view('avatar-form');
    }

    private function getSharedData($user) {// we can only call this function from this class
        // we have to always initialize the $currentlyFollowing variable to zero to make sure it never gets greater than one
        $currentlyFollowing = 0;
        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->count();
        }
        // this static function allows us to share data directly to the blade.php files
        View::share('sharedData', [
            'username'=>$user->username,
            'postCount'=>$user->posts()->count(),
            'avatar'=>$user->avatar,
            'currentlyFollowing'=>$currentlyFollowing,
            'followersCount'=>$user->followers()->count(),
            'followingCount'=>$user->following()->count(),
        ]);
    }

    public function profilePost(User $user) {
        $this->getSharedData($user);
        return view('profile-post', ['posts'=>$user->posts()->latest()->get(),]);
    }
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers'=>$user->followers()->latest()->get()]);
    } 
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following'=>$user->following()->latest()->get()]);
    } 

    public function logout() {
        event(new OurExampleEvent(['username'=>auth()->user()->username, 'action'=>'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'Logout Succesful');
    }

    public function showCorrectHomepage() {
     if (auth()->check()) {
        return view('homepage-feed', ['feedPosts'=> auth()->user()->feedPosts()->latest()->paginate(4)]);
     } else {
        return view('homepage');
     } 
    }
    
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername'=> 'required',
            'loginpassword'=> 'required'
        ]);
        if (auth()->attempt(['username'=> $incomingFields['loginusername'],'password'=>$incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            event(new OurExampleEvent(['username'=>auth()->user()->username, 'action'=>'login']));
            return redirect('/')->with('success', 'Login Successful');
        } else {
            return redirect('/')->with('error', 'Login Unsuccessful');
        }
    }

    public function register(Request $request) {
         /* here we call the validate method on the incoming fields variable where we spell out rules that the fields must 
        comply with */
        $incomingFields = $request->validate([
            /* the fields in the object are from the names in the html form */
            'username'=> ['required', 'min:3', 'max:30', Rule::unique('users', 'username')],
            'email'=> ['required', 'email', Rule::unique('users', 'email')],
            'password'=> ['required', 'min:8', 'confirmed']
        ]);

        /* here we hash the password values for before its sent to the database */
        $incomingFields['password'] = bcrypt($incomingFields['password']); 

        /* here we are using the User model to create a new user in the database */
        $user = User::create($incomingFields);

        /* here we are logging the user in automatically because he or she has created an account */
        auth()->login($user);
        
        /* the user is then redirected to the homePage  */
        return redirect('/')->with('success', 'Signup Succesful');
    }
}
