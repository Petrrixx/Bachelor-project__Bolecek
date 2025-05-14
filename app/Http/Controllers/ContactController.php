<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'surname'  => 'required|string|max:255',
            'phone'    => 'required|string|max:50',
            'email'    => 'required|email',
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string',
            'attachment' => 'nullable|file|max:10240', // do 10MB
        ]);

        // Pošleme mail na prevádzkový email
        Mail::to(config('mail.from.address'))
            ->send(new ContactFormMail($validated, $request->file('attachment')));

        return back()->with('success', 'Vaša správa bola odoslaná.');
    }
}

    // Future development, zatiaľ nepoužité

    /*
    public function deleteMultiple(Request $request) // nepoužitá
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['message' => 'Pole "ids" musí byť pole.'], 422);
        }

        $errors = [];
        foreach ($ids as $id) {
            try {
                $message = Message::find($id);
                if ($message) {
                    if ($message->attachment) {
                        Storage::disk('public')->delete($message->attachment);
                    }
                    $message->delete();
                } else {
                    $errors[] = "Správa s id $id nebola nájdená.";
                }
            } catch (\Exception $e) {
                $errors[] = "Chyba pri vymazávaní správy s id $id.";
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'message' => 'Niektoré správy sa nepodarilo vymazať.',
                'errors'  => $errors
            ], 500);
        }

        return response()->json(['message' => 'Vybrané správy boli vymazané.'], 200);
    }

    public function deleteAll()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        // Načítať všetky správy
        $messages = Message::all();

        // Pre každú správu zavoláme tú istú logiku vymazania
        foreach ($messages as $message) {
            $this->deleteSingleMessage($message);
        }

        return response()->json(['message' => 'Všetky správy boli vymazané.'], 200);
    }
    */
