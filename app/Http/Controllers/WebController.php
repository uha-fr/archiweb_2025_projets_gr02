<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Contract;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $kwhBalance = $user->kwh_balance;
        $walletBalance = $user->wallet_balance;

        return view('dashboard', compact('user', 'offers', 'contracts', 'activeOffers', 'completedContracts','kwhBalance', 'walletBalance'));
    }

    public function offers(Request $request)
{
    $userId = Auth::id();
    
    // Commencer avec toutes les offres actives
    $query = Offer::query()->where('status', 'active');
    
    // Exclure les offres qui ont des contrats actifs
    $query->whereDoesntHave('contracts', function($q) {
        $q->whereIn('status', ['active', 'completed']);
    });
    
    // Exclure les offres pour lesquelles l'utilisateur a déjà créé un contrat en attente
    $query->whereDoesntHave('contracts', function($q) use ($userId) {
        $q->where('status', 'pending')
          ->where(function($query) use ($userId) {
              $query->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
          });
    });

    // Exclure les offres de l'utilisateur lui-même
    $query->where('user_id', '!=', $userId);

    // Filtrage par type (offre ou demande)
    if ($request->has('type') && $request->type != '') {
        $query->where('type', $request->type);
    }

    // Filtrage par quantité minimale
    if ($request->has('min_quantity') && $request->min_quantity != '') {
        $query->where('quantity', '>=', $request->min_quantity);
    }

    // Filtrage par quantité maximale
    if ($request->has('max_quantity') && $request->max_quantity != '') {
        $query->where('quantity', '<=', $request->max_quantity);
    }

    // Filtrage par prix minimum
    if ($request->has('min_price') && $request->min_price != '') {
        $query->where('price', '>=', $request->min_price);
    }

    // Filtrage par prix maximum
    if ($request->has('max_price') && $request->max_price != '') {
        $query->where('price', '<=', $request->max_price);
    }

    $offers = $query->with('user')->paginate(10)->withQueryString();
    
    return view('offers.index', compact('offers'));
}

   
    
    /**
     * Supprimer une offre.
     */
    public function offerDestroy($id)
{
    $offer = Offer::findOrFail($id);
    
    // Vérifier que l'utilisateur est le propriétaire de l'offre
    if ($offer->user_id != Auth::id()) {
        return redirect()->route('offers.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette offre.');
    }
    
    // Vérifier si l'offre a des contrats actifs
    $hasActiveContracts = $offer->contracts()->whereIn('status', ['active', 'completed'])->exists();
    
    if ($hasActiveContracts) {
        return redirect()->route('offers.show', $offer->id)->with('error', 'Cette offre ne peut pas être supprimée car elle a des contrats actifs ou complétés.');
    }
    
    // Supprimer les contrats en attente associés à cette offre
    $offer->contracts()->where('status', 'pending')->delete();
    
    // Supprimer l'offre
    $offer->delete();
    
    return redirect()->route('offers.index')->with('success', 'Offre supprimée avec succès!');
}
    
    /**
     * Mettre à jour une offre.
     */
    public function offerUpdate(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        
        // Vérifier que l'utilisateur est le propriétaire de l'offre
        if ($offer->user_id != Auth::id()) {
            return redirect()->route('offers.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette offre.');
        }
        
        // Vérifier si l'offre a des contrats actifs
        $hasActiveContracts = $offer->contracts()->whereIn('status', ['active', 'completed'])->exists();
        
        if ($hasActiveContracts) {
            return redirect()->route('offers.show', $offer->id)->with('error', 'Cette offre ne peut pas être modifiée car elle a des contrats actifs ou complétés.');
        }
        
        $request->validate([
            'status' => 'required|in:active,cancelled',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        
        $offer->status = $request->status;
        $offer->quantity = $request->quantity;
        $offer->price = $request->price;
        $offer->start_time = $request->start_time;
        $offer->end_time = $request->end_time;
        $offer->save();
        
        return redirect()->route('offers.show', $offer->id)->with('success', 'Offre mise à jour avec succès!');
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

    
    
    public function contractStore(Request $request)
{
    $request->validate([
        'offer_id' => 'required|exists:offers,id',
    ]);

    $offer = Offer::findOrFail($request->offer_id);

    if ($offer->status !== 'active') {
        return redirect()->back()->with('error', 'Cette offre n\'est plus disponible.');
    }

    // Vérifier que l'utilisateur ne crée pas un contrat avec sa propre offre
    if ($offer->user_id == Auth::id()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas créer un contrat avec votre propre offre.');
    }

    $contract = DB::transaction(function () use ($offer, $request) {
        $contract = new Contract();
        $contract->offer_id = $offer->id;
        
        // Déterminer qui est l'acheteur et qui est le vendeur
        if ($offer->type == 'offer') {
            // Si c'est une offre de vente, l'utilisateur actuel est l'acheteur
            $contract->buyer_id = Auth::id();
            $contract->seller_id = $offer->user_id;
        } else {
            // Si c'est une demande d'achat, l'utilisateur actuel est le vendeur
            $contract->seller_id = Auth::id();
            $contract->buyer_id = $offer->user_id;
        }
        
        $contract->status = 'pending';
        $contract->save();

        // Ne pas changer le statut de l'offre pour qu'elle reste visible dans la liste
        // L'offre reste active et peut être utilisée pour d'autres contrats

        return $contract;
    });

    // Rediriger vers l'historique avec un message de succès
    return redirect()->route('history')->with('success', 'Contrat créé avec succès. Attendez la confirmation de l\'autre partie.');
}

/**
 * Afficher le formulaire de modification d'une offre.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function offerEdit($id)
{
    $offer = Offer::findOrFail($id);
    
    // Vérifier que l'utilisateur est le propriétaire de l'offre
    if ($offer->user_id != Auth::id()) {
        return redirect()->route('offers.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette offre.');
    }
    
    return view('offers.edit', compact('offer'));
}

/**
 * Mettre à jour une offre.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */

 

 public function offerToggleStatus(Request $request, $id)
{
    $offer = Offer::findOrFail($id);
    
    // Vérifier que l'utilisateur est le propriétaire de l'offre
    if ($offer->user_id != Auth::id()) {
        return redirect()->route('offers.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette offre.');
    }
    
    // Vérifier si l'offre a des contrats actifs
    $hasActiveContracts = $offer->contracts()->whereIn('status', ['active', 'completed'])->exists();
    
    if ($hasActiveContracts) {
        return redirect()->route('offers.show', $offer->id)->with('error', 'Cette offre ne peut pas être modifiée car elle a des contrats actifs ou complétés.');
    }
    
    // Basculer le statut
    $offer->status = ($offer->status == 'active') ? 'cancelled' : 'active';
    $offer->save();
    
    return redirect()->back()->with('success', 'Statut de l\'offre modifié avec succès!');
}


/**
 * Accepter un contrat en attente.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function contractAccept($id)
{
    $contract = Contract::findOrFail($id);

    // Vérification de l'autorisation de l'utilisateur
    if ($contract->seller_id != Auth::id()) {
        return redirect()->route('contracts.pending')->with('error', 'Vous n\'êtes pas autorisé à valider ce contrat.');
    }

    // Vérification du statut du contrat
    if ($contract->status != 'pending') {
        return redirect()->route('contracts.pending')->with('error', 'Ce contrat ne peut plus être validé.');
    }

    // Récupération des informations nécessaires
    $offer = $contract->offer;
    $seller = $contract->seller;
    $buyer = $contract->buyer;
    $quantity = $offer->quantity;
    $price = $offer->price;
    $total = $quantity * $price;

    // Vérification des soldes
    if ($seller->kwh_balance < $quantity) {
        return redirect()->route('contracts.pending')->with('error', 'Solde insuffisant en kWh pour finaliser le contrat.');
    }

    if ($buyer->wallet_balance < $total) {
        return redirect()->route('contracts.pending')->with('error', 'Solde insuffisant sur le portefeuille de l\'acheteur.');
    }

    // Démarrage de la transaction
    DB::transaction(function () use ($contract, $offer, $seller, $buyer, $quantity, $price, $total) {
        // Mise à jour du contrat et de l'offre
        $contract->status = 'active';
        $contract->save();

        $offer->status = 'matched';
        $offer->save();

        // Mise à jour des soldes
        $seller->kwh_balance -= $quantity;
        $seller->wallet_balance += $total;
        $buyer->kwh_balance += $quantity;
        $buyer->wallet_balance -= $total;

        // Sauvegarde des utilisateurs
        $seller->save();
        $buyer->save();

        // Enregistrement de la transaction
        Transaction::create([
            'contract_id' => $contract->id,
            'quantity' => $quantity,
            'price' => $price,
            'transaction_time' => now(),
        ]);
    });

    // Redirection avec message de succès
    return redirect()->route('contracts.pending')->with('success', 'Contrat accepté avec succès.');
}

public function solde()
{
    $user = Auth::user(); // Récupérer l'utilisateur connecté
    
    // Récupérer les informations sur le solde
    $kwhBalance = $user->kwh_balance;
    $walletBalance = $user->wallet_balance;
    
    // Retourner la vue pour afficher le solde
    return view('solde', compact('kwhBalance', 'walletBalance'));
}
public function compteur()
{
    $user = Auth::user(); // Récupérer l'utilisateur connecté
    
    // Compter le nombre d'offres créées par l'utilisateur
    $offresCount = $user->offers()->count();
    
    // Compter le nombre de contrats impliquant l'utilisateur (en tant qu'acheteur ou vendeur)
    $contratsCount = $user->contracts()->count();
    
    // Retourner la vue pour afficher le compteur
    return view('compteur', compact('offresCount', 'contratsCount'));
}
// Transactions KWh
public function transactionsKwh()
{
    $user = Auth::user();
    
    // Récupérer les transactions de KWh de l'utilisateur
    $transactions = Transaction::whereHas('contract', function($query) use ($user) {
        $query->where('buyer_id', $user->id)
              ->orWhere('seller_id', $user->id);
    })->with(['contract', 'contract.buyer', 'contract.seller'])
      ->latest()
      ->paginate(10);
    
    return view('transactions.kwh', compact('transactions'));
}

// Transactions Solde
public function transactionsSolde()
{
    $user = Auth::user();
    
    // Récupérer les transactions liées au solde de l'utilisateur
    $transactions = Transaction::whereHas('contract', function($query) use ($user) {
        $query->where('buyer_id', $user->id)
              ->orWhere('seller_id', $user->id);
    })->with(['contract', 'contract.buyer', 'contract.seller'])
      ->latest()
      ->paginate(10);
    
    return view('transactions.solde', compact('transactions'));
}

/**
 * Refuser un contrat en attente.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function contractReject($id)
{
    $contract = Contract::findOrFail($id);
    
    // Vérifier que l'utilisateur est bien le vendeur
    if ($contract->seller_id != Auth::id()) {
        return redirect()->route('contracts.pending')->with('error', 'Vous n\'êtes pas autorisé à refuser ce contrat.');
    }
    
    // Vérifier que le contrat est bien en attente
    if ($contract->status != 'pending') {
        return redirect()->route('contracts.pending')->with('error', 'Ce contrat ne peut plus être refusé.');
    }
    
    // Mettre à jour le statut du contrat
    $contract->status = 'cancelled';
    $contract->save();
    
    return redirect()->route('contracts.pending')->with('success', 'Contrat refusé avec succès.');
}
public function contractShow($id)
{
    $contract = Contract::with(['offer', 'buyer', 'seller'])->findOrFail($id);
    
    // Vérifier que l'utilisateur est autorisé à voir ce contrat
    if ($contract->buyer_id != Auth::id() && $contract->seller_id != Auth::id()) {
        return redirect()->route('history')->with('error', 'Vous n\'êtes pas autorisé à voir ce contrat.');
    }
    
    return view('contracts.show', compact('contract'));
}
public function pendingContracts()
{
    $user = Auth::user();
    // Récupérer les contrats où l'utilisateur est vendeur et le statut est en attente
    $pendingContracts = Contract::where('seller_id', $user->id)
                               ->where('status', 'pending')
                               ->with(['offer', 'buyer'])
                               ->latest()
                               ->paginate(10);
    
    return view('contracts.pending', compact('pendingContracts'));
}
public function myPendingContracts()
{
    $user = Auth::user();
    
    // Récupérer les contrats où l'utilisateur est acheteur et le statut est en attente
    $pendingContractsAsBuyer = Contract::where('buyer_id', $user->id)
                             ->where('status', 'pending')
                             ->with(['offer', 'seller'])
                             ->latest()
                             ->get();
    
    // Récupérer les contrats où l'utilisateur est vendeur et le statut est en attente
    $pendingContractsAsSeller = Contract::where('seller_id', $user->id)
                               ->where('status', 'pending')
                               ->with(['offer', 'buyer'])
                               ->latest()
                               ->get();
    
    // Combiner les deux collections
    $pendingContracts = $pendingContractsAsBuyer->merge($pendingContractsAsSeller)->sortByDesc('created_at');
    
    // Paginer manuellement
    $perPage = 10;
    $currentPage = request()->get('page', 1);
    $currentItems = $pendingContracts->forPage($currentPage, $perPage);
    $paginatedContracts = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems, 
        $pendingContracts->count(), 
        $perPage, 
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    return view('contracts.my-pending', compact('paginatedContracts'));
}

/**
 * Afficher le profil de l'utilisateur.
 *
 * @return \Illuminate\Http\Response
 */
public function profile()
{
    $user = Auth::user();
    return view('profile.show', compact('user'));
}

/**
 * Afficher le formulaire d'édition du profil.
 *
 * @return \Illuminate\Http\Response
 */
public function profileEdit()
{
    $user = Auth::user();
    return view('profile.edit', compact('user'));
}

/**
 * Mettre à jour le profil de l'utilisateur.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function profileUpdate(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'company_name' => $user->role == 'company' ? 'required|string|max:255' : 'nullable|string|max:255',
        'tax_id' => $user->role == 'company' ? 'required|string|max:50' : 'nullable|string|max:50',
        'bio' => 'nullable|string|max:1000',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Mise à jour des informations de base
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->address = $request->address;
    $user->company_name = $request->company_name;
    $user->tax_id = $request->tax_id;
    $user->bio = $request->bio;
    
    // Traitement de la photo de profil
    if ($request->hasFile('profile_photo')) {
        // Supprimer l'ancienne photo si elle existe
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        // Stocker la nouvelle photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo = $path;
    }
    
    $user->save();
    
    return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès!');
}

/**
 * Supprimer la photo de profil de l'utilisateur.
 *
 * @return \Illuminate\Http\Response
 */
public function profilePhotoDelete()
{
    $user = Auth::user();
    
    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);
        $user->profile_photo = null;
        $user->save();
    }
    
    return redirect()->route('profile.edit')->with('success', 'Photo de profil supprimée avec succès!');
}
}
