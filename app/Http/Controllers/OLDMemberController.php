<? ?> tinggal di hilangkan saja //

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OLDMemberController extends Controller
{
    public function index()
    {
        $members = User::with('member')
            ->whereIn('role', ['superadmin', 'admin', 'pengurus', 'anggota'])
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
            $request->validate([
                'name'     => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email'    => 'required|email:rfc,dns|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role'     => 'required|in:superadmin,admin,pengurus,anggota',
                'no_hp'    => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
                'alamat'   => 'nullable|string|max:500',
                'divisi'   => 'nullable|string|max:100',
                'nim'      => 'nullable|string|max:20',
                'angkatan' => 'nullable|digits:2',
            ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'is_active' => true,
            ]);

            Member::create([
                'user_id'  => $user->id,
                'nim'      => $request->nim,
                'angkatan' => $request->angkatan,
                'divisi'   => $request->divisi,
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
                'status'   => 'aktif',
            ]);
            LogActivity::log('create', 'Menambah anggota baru: ' . $request->name, 'User');
        });
        

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
    $user = User::with('member')->findOrFail($id);
    return view('members.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        //defendsive: cek role yang menjalan kan aksi
        if(!in_array($currentUser->role, ['superadmin', 'admin'])){
            abort(403, 'akses Ditolak');
        }

        //Admin tidak bisa edit superadmin
        if($user->role ==='superadmin' && $currentUser->role !=='superadmin'){
            return redirect()->route('members.index')->with('error', 'maaf, anda tidak dapat mengedit super admin');
        }

        //admin tidak dapat downgrade
        if($currentUser->id === $user->id && $request->role !== $currentUser->role){
            return redirect()->route('members.edit',$id)->with('error', 'maaf, anda tidak bisa mengubah role diri sendiri');
        }

        //admin tidak bisa upgrade ke superadmin
        if($currentUser->role === 'admin' && $request->role ==='superadmin'){
            return redirect()->route('members.edit', $id)->with('error', 'maaf, anda tidak bisa naik tingkat ke superadmin');
        }

            $request->validate([
                'name'     => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email'    => 'required|email:rfc,dns|unique:users,email,' . $user->id,
                'role'     => 'required|in:superadmin,admin,pengurus,anggota',
                'no_hp'    => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
                'alamat'   => 'nullable|string|max:500',
                'divisi'   => 'nullable|string|max:100',
                'nim'      => 'nullable|string|max:20',
                'angkatan' => 'nullable|digits:2',
            ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'role'      => $request->role,
                'is_active' => $request->is_active == '1' ? true : false,
            ]);
            //sinkronisasi status
            $memberStatus = $request->is_active == '1' ? 'aktif' : 'nonaktif';

            $user->member()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim'      => $request->nim,
                    'angkatan' => $request->angkatan,
                    'divisi'   => $request->divisi,
                    'no_hp'    => $request->no_hp,
                    'alamat'   => $request->alamat,
                    'status'   => $memberStatus
                ]
            );
                LogActivity::log('update', 'Mengupdate anggota: ' . $user->name, 'User', $user->id);
        });


    return redirect()->route('members.index')->with('success', 'Anggota berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Defensive: cek role yang melakukan aksi
        if (!in_array($currentUser->role, ['superadmin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        //super admin tidak dapat di hapus
        if ($user->role=== 'superadmin'){
            return redirect()->route('members.index')->with('error', 'Super admin tidak dapat dihapus!');
        }
        //tidak bisa menghapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('members.index')->with('error', 'Kamu tidak dapat menghapus akun sendiri!');
        }
        //admin tidak bisa menghapus admin lain nya
        if($currentUser->role === 'admin' && $user->role ==='admin'){
            return redirect()->route('members.index')->with('error','Admin tidak bisa menghapus admin lainnya');
        }
        
            DB::transaction(function () use ($user) {
            LogActivity::log('delete', 'Menghapus anggota: ' . $user->name, 'User', $user->id);
            $user->member()->delete();
            $user->delete();
        });


        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus!');
    }

    public function resetPassword(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        //defendsive: cek role yang melakukan
        if(!in_array($currentUser->role, ['superadmin', 'admin'])){
            abort(403,'akses di tolak');
        }

        //admin tidak bisa reset password superadmin
        if($user->role === 'superadmin' && $currentUser->role !=='superadmin'){
            return redirect()->route('members.edit')->with('error', 'anda tidak bisa mereset nyaa');
        }
        //super admin tidak dapat di reset passwordnya
        if ($user->role === 'superadmin') {
            return redirect()->route('members.index')->with('error', 'Password superadmin tidak dapat direset!');
        }
        //admin tidak bisa mereset pass admin lainnya
        if($user->role ==='admin' && $currentUser->role === 'admin' && $currentUser->id !== $user->id){
            return redirect()->route('members.edit')->with('error', 'admin tidak dapat mereset admin lain nya');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        LogActivity::log('update', 'Mereset password anggota: ' . $user->name, 'User', $user->id);
        
        return redirect()->route('members.index')->with('success', 'Password berhasil direset!');
    }

    public function show(string $id){
        $user = User::with(['member', 'member.kaderisasi', 'member.attendances.event'])->findOrFail($id);

        return view('members.show', compact('user'));
    }
}