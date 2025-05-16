<?php

namespace App\Http\Controllers;

use App\Models\Masukan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MasukanController extends Controller
{
    public function index()
    {
        $feedbacks = Masukan::paginate(9);
        $user = Auth::user();
        return view('masukan.index', compact('feedbacks', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['name'] = Auth::user()->name;
        $validated['email'] = Auth::user()->email;

        Masukan::create($validated);

        return redirect()->route('masukan.index')->with('success', 'Umpan balik berhasil dikirim!');
    }

    public function admin()
    {
        $feedbacks = Masukan::with('user')->paginate(9);

        $unreadMasukanCount = Masukan::where('is_read', false)->count();

        Masukan::where('is_read', false)->update(['is_read' => true]);
    
        return view('masukan.admin', compact('feedbacks', 'unreadMasukanCount'));
    }
    


    public function destroy($id)
    {
        $Feedback = Masukan::findOrFail($id);
        $Feedback->delete();

        return redirect()->route('masukan.admin')->with('success', 'Umpan balik berhasil dihapus!');
    }

    public function mentor()
    {
        $mentorId = Auth::id();
        $userIds = User::where('mentor_id', $mentorId)->pluck('id');

        $feedbacks = Masukan::with('user')
            ->whereIn('user_id', $userIds)
            ->paginate(9);

        return view('masukan.mentor', compact('feedbacks'));
    }
    

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $Feedback = Masukan::findOrFail($id);
        $Feedback->reply = $request->reply;
        $Feedback->save();

        return redirect()->route('masukan.admin')->with('success', 'Balasan berhasil dikirim!');
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $unreadMasukanCount = Masukan::where('is_read', false)->count();
            $view->with('unreadMasukanCount', $unreadMasukanCount);
        });
    }





}
