<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;       
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index()
{
    $projects = \App\Models\Project::query()
        ->latest('id')
        ->take(6)
        ->get(['id','name','distiption','url', 'image','status',"slogan"]);
    $user = Auth::user();
    return Inertia::render('Home', [
        'projects' => $projects,
        'meta' => [
          'title' => 'Проекты',
          'description' => 'Мои последние проекты',
        ],
        'user' => $user ? [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
        ] : null,
    ]);
}

public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'password' => ['required', Rules\Password::defaults()],
    ]);

    $envName = (string) env('ADMIN');
    $envPass = (string) env('PASSWORD');

    if ($request->name === $envName && hash_equals($request->password, $envPass)) {
        $user = User::firstOrCreate(
            ['name' => $envName],
            ['password' => Hash::make($envPass), 'is_admin' => 1]
        );

        Auth::login($user, remember: true);
        return redirect()->route('home');
    }

    $user = User::where('name', $request->name)->first();

    if ($user) {
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['name' => 'Неверный логин или пароль'])
                ->onlyInput('name');
        }

        Auth::login($user, remember: true);
        return redirect()->route('home');
    }

    $user = User::create([
        'name'     => $request->name,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));
    Auth::login($user, remember: true);

    return redirect()->route('home');
}

public function showRegister()
{
 
    return Inertia::render('Register');
}
public function destroy($id)
{
    // Проверка прав
    if (!Auth::user()?->is_admin) {
        return back()->with('error', 'Вы не администратор!');
    }
    $project = Project::findOrFail($id);
    // Удаляем картинку если есть
    if ($project->image) {
        Storage::disk('public')->delete($project->image);
    }

    // Удаляем проект
    $project->delete();

    return back()->with('success', 'Проект удалён!');
}

public function store(Request $request)
{
    if (!Auth::user()?->is_admin) {
        return redirect()->route('home')->with('error', 'Вы не администратор!');
    }

    $validated = $request->validate([
        'name'       => ['required','string','max:255'],
        'distiption' => ['nullable','string'], 
        'url'        => ['nullable','url','max:1024'],
        'slogan'     => ['nullable','string','max:255'],
        'image'      => ['nullable','image','mimes:jpg,jpeg,png,webp,gif','max:2048'],
        'status'     => ['required','in:draft,published'],
    ]);

    $filename = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        // уникальное имя: timestamp + оригинальное расширение
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('projects', $filename, 'public');
    }

    $project = Project::create([
        'name'       => $validated['name'],
        'distiption' => $validated['distiption'] ?? null,
        'url'        => $validated['url'] ?? null,
        'slogan'     => $validated['slogan'] ?? null,
        'status'     => $validated['status'],
        'image'      => $filename, // только "xxx.png"
        'user_id'    => Auth::id(),
    ]);

    return redirect()->route('home')->with('success', 'Проект успешно создан!');
}
public function editProject(Request $request, Project $project)
{
    if (!Auth::user()?->is_admin) {
        return back()->with('error', 'Вы не администратор!');
    }

    $validated = $request->validate([
        'name'       => ['required','string','max:255'],
        'distiption' => ['nullable','string'],
        'url'        => ['nullable','url','max:1024'],
        'slogan'     => ['nullable','string','max:255'],
        'image'      => ['nullable','image','mimes:jpg,jpeg,png,webp,gif','max:2048'],
        'status'     => ['required','in:draft,published'],
    ]);

    $filename = $project->image; // старое значение
    if ($request->hasFile('image')) {
        // удалить старый файл
        if ($project->image && Storage::disk('public')->exists('projects/'.$project->image)) {
            Storage::disk('public')->delete('projects/'.$project->image);
        }

        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('projects', $filename, 'public');
    }

    $project->update([
        'name'       => $validated['name'],
        'distiption' => $validated['distiption'] ?? null,
        'url'        => $validated['url'] ?? null,
        'slogan'     => $validated['slogan'] ?? null,
        'status'     => $validated['status'],
        'image'      => $filename, // только "xxx.png"
    ]);

    return to_route('home')->with('success', 'Проект успешно обновлён!');
}

}