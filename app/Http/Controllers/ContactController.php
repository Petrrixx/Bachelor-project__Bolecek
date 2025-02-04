<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validácia dát
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048', // Max 2MB
        ]);

        // Uloženie prílohy (ak bola pridaná)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Vytvorenie a uloženie správy do databázy
        $message = Message::create([
            'name'       => $validated['name'],
            'surname'    => $validated['surname'],
            'phone'      => $validated['phone'],
            'email'      => $validated['email'],
            'subject'    => $validated['subject'],
            'message'    => $validated['message'],
            'attachment' => $attachmentPath,
        ]);

        // Presmerovanie s úspešnou správou
        return redirect()->back()->with('success', 'Správa bola úspešne odoslaná.');
    }

    public function indexAdmin(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'Nemáte prístup.');
        }

        $query = Message::query();

        // Filtrovanie podľa dátumu (interval)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Filtrovanie podľa e-mailu
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filtrovanie podľa predmetu
        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        // Triedenie – podľa dátumu vytvorenia
        if ($request->filled('sort_order')) {
            $query->orderBy('created_at', $request->sort_order);
        } else {
            // Predvolené triedenie: najnovšie správy
            $query->orderBy('created_at', 'desc');
        }

        $messages = $query->get();

        if ($request->wantsJson()) {
            return response()->json(['messages' => $messages], 200);
        }

        return view('contact.messagesAdmin', compact('messages'));
    }

    public function showAdmin($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'Nemáte prístup.');
        }

        $message = Message::find($id);
        if (!$message) {
            return redirect()->route('contact.messagesAdmin')->with('error', 'Správa nebola nájdená.');
        }

        return view('contact.messageDetail', compact('message'));
    }

    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Správa nebola nájdená.'], 404);
        }

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();
        return response()->json(['message' => 'Správa bola vymazaná.'], 200);
    }

    public function deleteMultiple(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Unauthorized'], 401);
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

    public function getMessage($id)
    {
        $message = Message::findOrFail($id);
        return response()->json($message);
    }

    public function adminMailbox()
    {
        $messages = Message::all();

        return view('contact.contactAdmin', compact('messages'));
    }
}
