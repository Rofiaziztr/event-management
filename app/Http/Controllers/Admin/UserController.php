<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // --- Filtering ---
        if ($request->filled('search')) {
            $field = $request->input('search_field', 'full_name');
            if (in_array($field, ['full_name', 'email'])) {
                $query->where($field, 'like', '%' . $request->search . '%');
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereHas('attendances')
                        ->orWhereHas('participatedEvents');
                });
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('attendances')
                        ->whereDoesntHave('participatedEvents');
            }
        }

        // --- Sorting dengan fallback ---
        $sort = $request->input('sort', 'full_name');
        $direction = $request->input('direction', 'asc');

        if (! in_array($sort, ['full_name', 'email'])) {
            $sort = 'full_name';
        }
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:255|unique:users,nip',
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'institution' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['participant'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Jika input institusi kosong, set ke PSDMBP.
        if (empty($validated['institution'])) {
            $validated['institution'] = 'PSDMBP';
        }

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user)
    {
        if ($user->role !== 'participant') {
            abort(403, 'Hanya participant yang bisa dilihat.');
        }
        $user->load('participatedEvents', 'attendances');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role !== 'participant') {
            abort(403, 'Hanya participant yang bisa diedit.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'participant') {
            abort(403, 'Hanya participant yang bisa diedit.');
        }

        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'institution' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['participant'])],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Jika input institusi kosong, set ke PSDMBP.
        if (empty($validated['institution'])) {
            $validated['institution'] = 'PSDMBP';
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // ... (method destroy dan export tetap sama) ...
     public function destroy(User $user)
    {
        Log::info('Destroy method called for user: ' . $user->id);
        if ($user->role !== 'participant') {
            abort(403, 'Hanya participant yang bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function export(Request $request)
    {
        Log::info('Export route hit for admin.users.export');
        
        // Collect filters from request
        $filters = [];
        
        if ($request->filled('division')) {
            $filters['division'] = $request->division;
        }
        
        if ($request->filled('institution')) {
            $filters['institution'] = $request->institution;
        }
        
        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }
        
        $filename = 'daftar_pengguna_' . now()->format('Ymd_His') . '.xlsx';
        
        return Excel::download(new UsersExport($filters), $filename);
    }
}