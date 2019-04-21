<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['storeFirstUser']]);
    }

    public function storeFirstUser(Request $request) {
        $user = new User;

        $user->fill($request->all());

        $user->password = Hash::make($request->password);
        $user->role = User::ROLE_ADMIN;

        $user->save();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            return redirect()->route('home');
        }

        return redirect()->route('home');
    }

    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::orderBy('name')->get();

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        $user = new User;

        return view('users.create', [
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $user = new User;

        $user->fill($request->all());

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('users.index')->with('flash.success', 'Usuário salvo com sucesso');
    }

    public function edit(User $user)
    {
        $this->authorize('edit', $user);

        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        $user->fill($request->all());

        if (!empty($request->new_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('users.index')->with('flash.success', 'Usuário salvo com sucesso');
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);

        $user->delete();

        return redirect()->route('users.index')->with('flash.success', 'Usuário excluído com sucesso');
    }

    public function profile()
    {
        $user = User::find(Auth::user()->id);

        return view('users.profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->fill($request->all());

        if (!empty($request->new_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('imagem')) {
            $extension = $request->file('imagem')->getClientOriginalExtension();

            $user->avatar_extension = $extension;
        }

        $user->save();

        if ($request->hasFile('imagem')) {
            $request->file('imagem')->move(base_path('/public/files/users'), sprintf('%s.%s', $user->id, $extension));
        }

        return redirect()->route('users.profile')->with('flash.success', 'Perfil salvo com sucesso');
    }

    public function checkEmail(Request $request)
    {
        if (!empty($request->id)) {
            $user = User::find($request->id);

            return (User::where('email', $request->email)
                ->where('email', '<>', $user->email)
                ->first()) ? 'false' : 'true';
        } else {
            return (User::where('email', $request->email)->first()) ? 'false' : 'true';
        }
    }

    public function checkProfileEmail(Request $request)
    {
        if ($request->email == Auth::user()->email) {
            return 'true';
        }

        $user = User::where('email', $request->input('email'))->first();

        return ($user) ? 'false' : 'true';
    }

    public function checkProfilePassword(Request $request)
    {
        $user = Auth::user();

        return (Hash::check($request->old_password, $user->password)) ? 'true' : 'false';
    }

    public function block(User $user) {
        $this->authorize('block', $user);

        $user->locked = true;

        $user->save();

        return redirect()->route('users.index')->with('flash.success', 'Usuário bloqueado com sucesso');
    }

    public function unblock(User $user) {
        $this->authorize('unblock', $user);

        $user->locked = false;

        $user->save();

        return redirect()->route('users.index')->with('flash.success', 'Usuário desbloqueado com sucesso');
    }
}
