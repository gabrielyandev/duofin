<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceInvitationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected WorkspaceInvitationService $invitationService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create initial workspace for the user
            $firstName = explode(' ', trim($request->name))[0];
            $workspace = Workspace::create([
                'name' => 'Finanças de '.$firstName,
                'invite_code' => $this->invitationService->generateUniqueInviteCode(),
            ]);

            $workspace->users()->attach($user->id, ['role' => 'owner']);

            $user->current_workspace_id = $workspace->id;
            $user->save();

            // Create initial essential categories
            $defaultCategories = [
                ['name' => 'Moradia', 'type' => 'expense', 'color' => '#6366f1', 'icon' => 'home'],
                ['name' => 'Mercado', 'type' => 'expense', 'color' => '#10b981', 'icon' => 'shopping-cart'],
                ['name' => 'Transporte', 'type' => 'expense', 'color' => '#f59e0b', 'icon' => 'car'],
                ['name' => 'Saúde', 'type' => 'expense', 'color' => '#ef4444', 'icon' => 'activity'],
                ['name' => 'Lazer', 'type' => 'expense', 'color' => '#8b5cf6', 'icon' => 'film'],
                ['name' => 'Assinaturas', 'type' => 'expense', 'color' => '#06b6d4', 'icon' => 'tv'],
                ['name' => 'Salário', 'type' => 'income', 'color' => '#10b981', 'icon' => 'briefcase'],
            ];

            foreach ($defaultCategories as $cat) {
                Category::create([
                    'workspace_id' => $workspace->id,
                    'name' => $cat['name'],
                    'type' => $cat['type'],
                    'color' => $cat['color'],
                    'icon' => $cat['icon'],
                ]);
            }

            // Create initial account
            Account::create([
                'workspace_id' => $workspace->id,
                'name' => 'Conta Corrente',
                'initial_balance' => 0.00,
                'color' => '#0ea5e9',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
