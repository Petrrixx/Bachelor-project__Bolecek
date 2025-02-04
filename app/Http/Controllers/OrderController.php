<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OrderController extends Controller
{
    /**
     * Uloženie novej objednávky.
     * POST /orders
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'notes'      => 'nullable|string',
        ]);

        // Overenie, či je zvolený čas medzi 10:00 a 15:00
        $orderTime = Carbon::createFromFormat('H:i', $validated['order_time']);
        $minTime   = Carbon::createFromFormat('H:i', '10:00');
        $maxTime   = Carbon::createFromFormat('H:i', '15:00');
        if ($orderTime->lessThan($minTime) || $orderTime->greaterThan($maxTime)) {
            return response()->json(['message' => 'Čas objednávky musí byť medzi 10:00 a 15:00.'], 422);
        }

        $order = Order::create([
            'user_id'    => Auth::id(),
            'order_date' => $validated['order_date'],
            'order_time' => $validated['order_time'],
            'status'     => 'PENDING',
            'notes'      => $validated['notes'] ?? null,
        ]);

        // Nastavenie dátumu/času vytvorenia, ak databáza to nerieši automaticky
        $order->created_date = Carbon::now()->toDateString();
        $order->created_time = Carbon::now()->format('H:i:s');
        $order->save();

        return response()->json([
            'message' => 'Objednávka bola úspešne odoslaná.',
            'order'   => $order,
        ], 200);
    }

    /**
     * Úprava objednávky používateľom.
     * PATCH /orders/{id}
     */
    public function update(Request $request, $id)
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

        $validated = $request->validate([
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'notes'      => 'nullable|string',
        ]);

        $orderTime = Carbon::createFromFormat('H:i', $validated['order_time']);
        $minTime   = Carbon::createFromFormat('H:i', '10:00');
        $maxTime   = Carbon::createFromFormat('H:i', '15:00');
        if ($orderTime->lessThan($minTime) || $orderTime->greaterThan($maxTime)) {
            return response()->json(['message' => 'Čas objednávky musí byť medzi 10:00 a 15:00.'], 422);
        }

        $order->order_date = $validated['order_date'];
        $order->order_time = $validated['order_time'];
        $order->notes      = $validated['notes'] ?? $order->notes;
        $order->save();

        return response()->json([
            'message' => 'Objednávka bola upravená.',
            'order'   => $order,
        ], 200);
    }

    /**
     * Zrušenie objednávky používateľom (nastavením statusu na CANCELED).
     * PATCH /orders/{id}/cancel
     */
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

    /**
     * Zobrazenie objednávok aktuálne prihláseného používateľa.
     * GET /orders/user
     */
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

    /**
     * Zobrazenie objednávok pre administrátora s pokročilým filtrovaním.
     * GET /orders/admin
     *
     * Filteruje sa podľa:
     * - Dátum: 'date_from' a 'date_to'
     * - Čas: 'time_from' a 'time_to'
     * - Stav: pole 'status_filter[]' (napr. PENDING, ACCEPTED, DECLINED, CANCELED)
     * - Triedenie: 'sort_order' s hodnotami "earliest" (order_time ASC) alebo "latest" (order_time DESC)
     */
    public function indexAdmin(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'Nemáte prístup.');
        }

        $query = Order::with('user');

        // Filtrovanie podľa dátumu (interval)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('order_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        // Filtrovanie podľa času (interval)
        if ($request->filled('time_from') && $request->filled('time_to')) {
            $query->whereBetween('order_time', [$request->time_from, $request->time_to]);
        } elseif ($request->filled('time_from')) {
            $query->where('order_time', '>=', $request->time_from);
        } elseif ($request->filled('time_to')) {
            $query->where('order_time', '<=', $request->time_to);
        }

        // Filtrovanie podľa stavu (checkboxy)
        if ($request->filled('status_filter')) {
            $statuses = $request->status_filter;
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }
            $query->whereIn('status', $statuses);
        }

        // Triedenie – podľa času objednávky (najskoršie / najstaršie)
        if ($request->filled('sort_order')) {
            if ($request->sort_order === 'earliest') {
                $query->orderBy('order_time', 'asc');
            } elseif ($request->sort_order === 'latest') {
                $query->orderBy('order_time', 'desc');
            }
        } else {
            // Predvolený rad: najnovšie vytvorené objednávky
            $query->orderBy('created_date', 'desc')
                ->orderBy('created_time', 'desc');
        }

        $orders = $query->get();

        if ($request->wantsJson()) {
            return response()->json(['orders' => $orders], 200);
        }

        return view('orders.ordersAdmin', compact('orders'));
    }

    /**
     * Aktualizácia stavu objednávky administrátorom.
     * PATCH /orders/{id}/update-status
     *
     * Povolené stavy: PENDING, ACCEPTED, DECLINED
     * (ak je objednávka zrušená, aktualizácia nie je povolená)
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Unauthorized'], 401);
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

        return response()->json([
            'message' => 'Status objednávky bol aktualizovaný.',
            'order'   => $order,
        ], 200);
    }

    /**
     * Fyzické vymazanie objednávky administrátorom.
     * DELETE /orders/{id}
     */
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $order = Order::query()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Objednávka nebola nájdená.'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Objednávka bola vymazaná.'], 200);
    }

    /**
     * Hromadné fyzické vymazanie objednávok administrátorom.
     * DELETE /orders/delete-multiple
     */
    /**
     * Hromadné fyzické vymazanie objednávok administrátorom.
     * DELETE /orders/delete-multiple
     */
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
}

