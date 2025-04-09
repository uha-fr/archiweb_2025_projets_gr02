<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Contract;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $offers = $user->offers()->latest()->take(5)->get();
        $contracts = $user->contracts()->latest()->take(5)->get();
        
        // Statistics
        $activeOffers = $user->offers()->where('status', 'active')->count();
        $completedContracts = $user->contracts()->where('status', 'completed')->count();
        
        return view('dashboard', compact('user', 'offers', 'contracts', 'activeOffers', 'completedContracts'));
    }

    public function offers(Request $request)
    {
        $query = Offer::query()->where('status', 'active');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('min_quantity')) {
            $query->where('quantity', '>=', $request->min_quantity);
        }

        if ($request->has('max_quantity')) {
            $query->where('quantity', '<=', $request->max_quantity);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $offers = $query->paginate(10);
        
        return view('offers.index', compact('offers'));
    }

    public function offerShow($id)
    {
        $offer = Offer::findOrFail($id);
        return view('offers.show', compact('offer'));
    }

    public function offerCreate()
    {
        return view('offers.create');
    }

    public function offerStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:offer,demand',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $offer = new Offer();
        $offer->user_id = Auth::id();
        $offer->type = $request->type;
        $offer->quantity = $request->quantity;
        $offer->price = $request->price;
        $offer->start_time = $request->start_time;
        $offer->end_time = $request->end_time;
        $offer->status = 'active';
        $offer->save();

        return redirect()->route('offers.show', $offer->id)->with('success', 'Offre créée avec succès!');
    }

    public function history()
    {
        $user = Auth::user();
        $contracts = $user->contracts()->latest()->paginate(10);
        
        return view('history', compact('contracts'));
    }
}
