<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Contract;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('contract')
            ->latest()
            ->paginate(10); // 10 transactions par page

        return view('transactions.index', compact('transactions'));
    }


}

?>