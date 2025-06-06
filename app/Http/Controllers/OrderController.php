<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderAdminMail;
use App\Mail\OrderAcceptedMail;
use App\Mail\OrderRejectedMail;

class OrderController extends Controller
{
    /**
     * Uloženie novej objednávky (dostupné verejne).
     */
    public function store(Request $request)
    {
        // 1) Základná validácia
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'user_contact'     => 'required|string|max:50',
            'order_date'       => 'required|date|date_format:Y-m-d',
            'order_type'       => 'required|in:Osobný odber,Rozvoz',
            'pickup_time'      => ['nullable', 'required_if:order_type,Osobný odber', 'date_format:H:i'],
            'delivery_address' => ['nullable', 'required_if:order_type,Rozvoz', 'string', 'max:255'],
            'notes'            => 'nullable|string',
        ]);

         // Serverová kontrola času (len pri - Osobný odber)
        $formattedTime = null;
        if ($validated['order_type'] === 'Osobný odber') {
            $time = Carbon::createFromFormat('H:i', $validated['pickup_time']);
            $min  = Carbon::createFromFormat('H:i', '10:00');
            $max  = Carbon::createFromFormat('H:i', '15:00');
            if ($time->lt($min) || $time->gt($max)) {
                return redirect()->back()
                    ->withErrors(['pickup_time' => 'Čas príchodu musí byť medzi 10:00 a 15:00.'])
                    ->withInput();
            }
            $formattedTime = $time->format('H:i:s');
        }

        // 3) Vytvorenie záznamu
        $order = Order::create([
            'user_fullname'   => $validated['name'],
            'user_email'      => $validated['email'],
            'user_contact'    => $validated['user_contact'],
            'order_date'      => $validated['order_date'],
            'order_time'      => $formattedTime,
            'order_type'      => $validated['order_type'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'status'          => 'PENDING',
            'created_date'    => Carbon::now()->toDateString(),
            'created_time'    => Carbon::now()->format('H:i:s'),
        ]);

        // Admin email
        Mail::to(config('mail.from.address'))
        ->send(new OrderAdminMail($order));

        // Zákazník potvrdenie
        Mail::to($order->user_email)
        ->send(new OrderConfirmationMail($order));

        return redirect()
        ->back()
        ->with('success', 'Objednávka bola úspešne odoslaná.');
    }

    /**
     * Admin – zobrazenie všetkých objednávok.
     */
    public function indexAdmin(Request $request)
    {
        if (!Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte prístup.');
        }

        $query = Order::query();

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('order_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        if ($request->filled('time_from') && $request->filled('time_to')) {
            $query->whereBetween('order_time', [$request->time_from, $request->time_to]);
        } elseif ($request->filled('time_from')) {
            $query->where('order_time', '>=', $request->time_from);
        } elseif ($request->filled('time_to')) {
            $query->where('order_time', '<=', $request->time_to);
        }

        if ($request->filled('status_filter')) {
            $statuses = $request->status_filter;
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }
            $query->whereIn('status', $statuses);
        }

        if ($request->filled('sort_order')) {
            if ($request->sort_order === 'earliest') {
                $query->orderBy('order_time', 'asc');
            } elseif ($request->sort_order === 'latest') {
                $query->orderBy('order_time', 'desc');
            }
        } else {
            $query->orderBy('created_date', 'desc')
                ->orderBy('created_time', 'desc');
        }

        $orders = $query->get();

        if ($request->wantsJson()) {
            return response()->json(['orders' => $orders], 200);
        }

        return view('orders.ordersAdmin', compact('orders'));
    }

    // Celková update metóda (ak by sa používala aj inde)
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená alebo vám nepatrí.'], 404);
        }

        // Tu sa aktualizuje celý záznam – aj dátum
        $validated = $request->validate([
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'order_type' => 'required|in:Osobný odber,Rozvoz',
            'notes'      => 'nullable|string',
        ]);

        $orderTime = Carbon::createFromFormat('H:i', $validated['order_time']);
        $minTime   = Carbon::createFromFormat('H:i', '10:00');
        $maxTime   = Carbon::createFromFormat('H:i', '15:00');
        if ($orderTime->lessThan($minTime) || $orderTime->greaterThan($maxTime)) {
            return response()->json(['message' => 'Čas objednávky musí byť medzi 10:00 a 15:00.'], 422);
        }

        $formattedTime = $orderTime->format('H:i:s');

        $order->order_date = $validated['order_date'];
        $order->order_time = $formattedTime;
        $order->order_type = $validated['order_type'];
        $order->notes      = $validated['notes'] ?? $order->notes;
        $order->save();

        return response()->json([
            'message' => 'Objednávka bola upravená.',
            'order'   => $order,
        ], 200);
    }

    /*
    // Update objednávky pre používateľa – aktualizuje len čas, typ objednávky a poznámky; dátum sa nemení
    public function userUpdate(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená alebo vám nepatrí.'], 404);
        }

        // Ak je objednávka už ACCEPTED alebo DECLINED, nepovoľujeme úpravu
        if (in_array($order->status, ['ACCEPTED', 'DECLINED'])) {
            return response()->json([
                'message' => 'Objednávku nemožno upravovať, pretože už bola akceptovaná alebo odmietnutá.'
            ], 422);
        }

        $validated = $request->validate([
            'order_time' => 'required|date_format:H:i',
            'order_type' => 'required|in:Osobný odber,Rozvoz',
            'notes'      => 'nullable|string',
        ]);

        $orderTime = Carbon::createFromFormat('H:i', $validated['order_time']);
        $minTime   = Carbon::createFromFormat('H:i', '10:00');
        $maxTime   = Carbon::createFromFormat('H:i', '15:00');
        if ($orderTime->lessThan($minTime) || $orderTime->greaterThan($maxTime)) {
            return response()->json(['message' => 'Čas objednávky musí byť medzi 10:00 a 15:00.'], 422);
        }

        $formattedTime = $orderTime->format('H:i:s');

        // Dátum sa nemení, aktualizujeme len čas, typ a poznámky
        $order->order_time = $formattedTime;
        $order->order_type = $validated['order_type'];
        $order->notes      = $validated['notes'] ?? $order->notes;
        $order->save();

        return response()->json([
            'message' => 'Objednávka bola upravená.',
            'order'   => $order,
        ], 200);
    }
    */

    /*
    // Zrušenie objednávky používateľom
    public function cancel(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená alebo vám nepatrí.'], 404);
        }

        $order->status = 'CANCELED';
        $order->save();

        return response()->json([
            'message' => 'Objednávka bola zrušená.',
            'order'   => $order,
        ], 200);
    }
    */

    // Zobrazenie objednávok pre používateľa (index)
    /*
    public function indexUser()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.auth', ['type' => 'login']);
        }

        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_date', 'desc')
            ->orderBy('created_time', 'desc')
            ->get();

        return view('orders.ordersUser', compact('orders'));
    }
    */

    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $validated = $request->validate([
            'status' => 'required|in:PENDING,ACCEPTED,DECLINED'
        ]);

        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená.'], 404);
        }

        if ($order->status === 'CANCELED') {
            return response()->json(['message' => 'Objednávka je zrušená a nie je možné meniť jej stav.'], 422);
        }

        $order->status = $validated['status'];
        $order->save();

        // pošleme maily podľa statusu:
        if ($order->status === 'ACCEPTED') {
            Mail::to($order->user_email)
                ->send(new OrderAcceptedMail($order));
        } elseif ($order->status === 'DECLINED') {
            Mail::to($order->user_email)
                ->send(new OrderRejectedMail($order));
        } else {
            // PENDING – nič sa nedeje
        }

        return response()->json([
            'message' => 'Status objednávky bol aktualizovaný.',
            'order'   => $order,
        ], 200);
    }

    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená.'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Objednávka bola vymazaná.'], 200);
    }

    public function deleteMultiple(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte oprávnenie'], 401);
        }

        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['message' => 'Pole "ids" musí byť pole.'], 422);
        }

        $errors = [];
        foreach ($ids as $id) {
            try {
                $order = Order::query()->find($id);
                if ($order) {
                    $order->delete();
                } else {
                    $errors[] = "Objednávka s id $id nebola nájdená.";
                }
            } catch (\Exception $e) {
                Log::error("Chyba pri vymazávaní objednávky s id $id: " . $e->getMessage());
                $errors[] = "Chyba pri vymazávaní objednávky s id $id.";
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'message' => 'Niektoré objednávky sa nepodarilo vymazať.',
                'errors'  => $errors
            ], 500);
        }

        return response()->json(['message' => 'Vybrané objednávky boli vymazané.'], 200);
    }

    public function create() 
    {
        return view('orders.orders');
    }
}
