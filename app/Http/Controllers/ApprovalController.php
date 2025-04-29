<?php

namespace App\Http\Controllers;

use App\Helpers\QrSignatureHelper;
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
            'qr_code_content' => QrSignatureHelper::generateForStage($request, Auth::user(), 'itm_initial_review'),
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

        if ($request->status !== 'waiting_itm_final_review') {
            return back()->with('error', 'Request tidak berada dalam status menunggu persetujuan akhir.');
        }

        $request->update([
            'status' => 'completed',
        ]);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'itm_final_review',
            'approved_at' => now(),
            'verification_status' => 'Close',
            'qr_code_content' => QrSignatureHelper::generateForStage($request, Auth::user(), 'itm_final_review'),
        ]);

        return back()->with('success', 'Request telah disetujui secara final oleh ITM.');
    }

    public function handleITMFinalReviewReject($id)
    {
        $request = PtppRequest::findOrFail($id);

        if ($request->status !== 'waiting_itm_final_review') {
            return back()->with('error', 'Request tidak berada dalam status menunggu persetujuan akhir.');
        }

        $request->update([
            'status' => 'rejected',
        ]);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'itm_final_review',
            'approved_at' => now(),
            'verification_status' => 'Follow Up',
            'next_verification_target' => now()->addDays(30),
            'qr_code_content' => QrSignatureHelper::generateForStage($request, Auth::user(), 'itm_final_review'),
        ]);

        return back()->with('success', 'Request telah ditolak oleh ITM pada tahap akhir.');
    }

    public function handleRequesterReviewApprove(PtppRequest $request)
    {
        $request->update([
            'status' => 'waiting_itm_final_review',
        ]);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'requester_review',
            'approved_at' => now(),
            'qr_code_content' => QrSignatureHelper::generateForStage($request, Auth::user(), 'requester_review'),
        ]);

        return redirect()->back()->with('success', 'Request disetujui oleh pemohon.');
    }

    public function handleRequesterReviewReject(PtppRequest $request)
    {
        $request->update([
            'status' => 'rejected',
        ]);

        Approval::create([
            'request_id' => $request->id,
            'approver_user_id' => Auth::id(),
            'stage' => 'requester_review',
            'approved_at' => now(),
            'qr_code_content' => QrSignatureHelper::generateForStage($request, Auth::user(), 'requester_review'),
        ]);

        return redirect()->back()->with('success', 'Request ditolak oleh pemohon.');
    }
}
