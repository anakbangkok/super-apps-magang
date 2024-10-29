<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        $user = Auth::user();
        return view('feedback.index', compact('feedbacks', 'user'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'message' => 'required|string',
        ]);
    

        $validated['name'] = Auth::user()->name;
        $validated['email'] = Auth::user()->email;
    
        Feedback::create($validated);
    
    
        return redirect()->route('feedback.index')->with('success', 'Umpan balik berhasil dikirim!');
    }

    public function admin()
    {
        $feedbacks = Feedback::all(); // Mengambil semua feedback
        return view('feedback.admin', compact('feedbacks'));
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedback.admin')->with('success', 'Umpan balik berhasil dihapus!');
    }

    public function mentor()
    {
        $feedbacks = Feedback::all();
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

    


   
}
