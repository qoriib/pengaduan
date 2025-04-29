<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\PtppRequest;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{

    public function showITMApproval()
    {
        $initialRequests = PtppRequest::where('status', 'waiting_itm_initial_review')->get();
        $finalRequests = PtppRequest::where('status', 'waiting_itm_final_review')->get();

        return view('approval.index', compact('initialRequests', 'finalRequests'));
    }

    public function handleITMInitialReviewApprove($id)
    {
        $request = PtppRequest::findOrFail($id);
        $request->update([
            'status' => 'waiting_executor'
        ]);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'itm_initial_review',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Request disetujui dan dikirim ke executor.');
    }

    public function handleITMInitialReviewReject($id)
    {
        $request = PtppRequest::findOrFail($id);
        $request->update([
            'status' => 'rejected'
        ]);

        return redirect()->back()->with('success', 'Request ditolak.');
    }

    public function handleITMFinalReviewApprove($id)
    {
        $request = PtppRequest::findOrFail($id);

        // Validasi status harus menunggu persetujuan final ITM
        if ($request->status !== 'waiting_itm_final_review') {
            return back()->with('error', 'Request tidak berada dalam status menunggu persetujuan akhir.');
        }

        // Update request status menjadi selesai / close
        $request->update([
            'status' => 'completed',
        ]);

        // Tambahkan entri ke tabel approvals sebagai final approve
        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'itm_final_review',
            'approved_at' => now(),
            'verification_status' => 'Close',
        ]);

        return back()->with('success', 'Request telah disetujui secara final oleh ITM.');
    }

    public function handleITMFinalReviewReject($id)
    {
        $request = PtppRequest::findOrFail($id);

        // Validasi status harus menunggu persetujuan final ITM
        if ($request->status !== 'waiting_itm_final_review') {
            return back()->with('error', 'Request tidak berada dalam status menunggu persetujuan akhir.');
        }

        // Update status menjadi rejected
        $request->update([
            'status' => 'rejected',
        ]);

        // Tambahkan entri ke tabel approvals sebagai reject final
        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'itm_final_review',
            'approved_at' => now(),
            'verification_status' => 'Follow Up',
            'next_verification_target' => now()->addDays(30), // misalnya 30 hari ke depan
        ]);

        return back()->with('success', 'Request telah ditolak oleh ITM pada tahap akhir.');
    }

    public function handleRequesterReviewApprove(PtppRequest $request)
    {
        // Update status request
        $request->update([
            'status' => 'waiting_itm_final_review', // lanjut ke ITM Final Approval
        ]);

        // Tambah entry approval
        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'requester_review',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Request disetujui oleh pengaju.');
    }

    public function handleRequesterReviewReject(PtppRequest $request)
    {
        // Update status request
        $request->update([
            'status' => 'rejected',
        ]);

        // Tambah entry approval
        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'requester_review',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Request ditolak oleh pengaju.');
    }
}
