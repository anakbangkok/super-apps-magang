<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::paginate(9);
        $user = Auth::user();
        return view('feedback.index', compact('feedbacks', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id(); // Mendapatkan ID pengguna yang sedang login
        $validated['name'] = Auth::user()->name;
        $validated['email'] = Auth::user()->email;

        Feedback::create($validated);

        return redirect()->route('feedback.index')->with('success', 'Umpan balik berhasil dikirim!');
    }


    public function admin()
    {
        $feedbacks = Feedback::with('user')->paginate(9); // Mengambil feedback dengan relasi user
        // Hitung feedback yang belum dibaca
        $unreadFeedbackCount = Feedback::where('is_read', false)->count();
    
        // Tandai semua feedback sebagai dibaca
        Feedback::where('is_read', false)->update(['is_read' => true]);
    
        return view('feedback.admin', compact('feedbacks', 'unreadFeedbackCount'));
    }
    


    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedback.admin')->with('success', 'Umpan balik berhasil dihapus!');
    }
    public function mentor()
    {
        $feedbacks = Feedback::paginate(9); // Ambil feedback dengan paginasi
        return view('feedback.mentor', compact('feedbacks'));
    }
    

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->reply = $request->reply;
        $feedback->save();

        return redirect()->route('feedback.admin')->with('success', 'Balasan berhasil dikirim!');
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $unreadFeedbackCount = Feedback::where('is_read', false)->count();
            $view->with('unreadFeedbackCount', $unreadFeedbackCount);
        });
    }





}
