<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\PtppRequest;
use App\Models\RequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestDetailController extends Controller
{
    public function create(PtppRequest $request)
    {
        if ($request->to_user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('request_details.create', compact('request'));
    }

    public function store(Request $req, PtppRequest $request)
    {
        $validated = $req->validate([
            'received_at' => 'required|date',
            'temporary_repair' => 'nullable|string',
            'cause_analysis' => 'required|string',
            'correction_action' => 'required|string',
            'pic' => 'required|string',
            'execution_time' => 'required|date',
            'document_revised' => 'nullable|string',
            'target_verification_date' => 'nullable|date',
        ]);

        $validated['request_id'] = $request->id;
        $validated['resolver_user_id'] = Auth::id();

        RequestDetail::create($validated);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'executor_response',
            'approved_at' => now(),
            'qr_code_path' => null,
            'verification_status' => null,
            'next_verification_target' => null,
        ]);

        $request->update(['status' => 'waiting_requester_review']);

        return redirect()->route('requests.show')->with('success', 'Detail perbaikan berhasil dikirim.');
    }
}
