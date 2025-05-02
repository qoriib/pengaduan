<?php

namespace App\Http\Controllers;

use App\Exports\MwtReportExport;
use App\Models\MwtReport;
use App\Models\MwtParticipant;
use App\Models\User;
use App\Helpers\QrSignatureHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MwtReportController extends Controller
{
    public function showRequests()
    {
        $user = Auth::user();

        if ($user->role === 'ITM') {
            $reports = MwtReport::with(['participants.user', 'acknowledger', 'approver'])
                ->latest()
                ->get();
        } else {
            $reports = MwtReport::with(['participants.user', 'acknowledger', 'approver'])
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orWhere('acknowledged_by', $user->id)
                ->latest()
                ->get();
        }

        return view('mwt_requests.index', compact('reports'));
    }

    public function showDetail($id)
    {
        $report = MwtReport::with('participants.user', 'acknowledger', 'approver')->findOrFail($id);
        return view('mwt_requests.detail', compact('report'));
    }

    public function showCreate()
    {
        $users = User::all();
        return view('mwt_requests.create', compact('users'));
    }

    public function handleCreate(Request $request)
    {
        $validated = $request->validate([
            'execution_date' => 'required|date',
            'dialogues' => 'nullable|array',
            'positive_findings' => 'nullable|array',
            'unsafe_conditions' => 'nullable|array',
            'documentation' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
        ]);

        $docPath = $request->hasFile('documentation')
            ? $request->file('documentation')->store('mwt_docs', 'public')
            : null;

        $ackUser = Auth::user();

        $report = MwtReport::create([
            'execution_date' => $validated['execution_date'],
            'dialogues' => $validated['dialogues'],
            'positive_findings' => $validated['positive_findings'],
            'unsafe_conditions' => $validated['unsafe_conditions'],
            'documentation_path' => $docPath,
            'acknowledged_by' => $ackUser->id,
            'acknowledged_by_qr_code_content' => QrSignatureHelper::generateForMWT(null, $ackUser, 'acknowledged'),
        ]);

        foreach ($validated['participants'] as $participantId) {
            $participantUser = User::find($participantId);
            MwtParticipant::create([
                'mwt_report_id' => $report->id,
                'user_id' => $participantId,
                'qr_code_content' => QrSignatureHelper::generateForMWT($report->id, $participantUser, 'participant'),
            ]);
        }

        return redirect()->route('mwt-requests.show')->with('success', 'Laporan MWT berhasil diajukan.');
    }

    public function handleApprove(Request $request, $id)
    {
        $report = MwtReport::findOrFail($id);

        if (Auth::user()->role !== 'ITM') {
            abort(403, 'Hanya user dengan role ITM yang dapat menyetujui laporan.');
        }

        $report->update([
            'approved_by' => Auth::id(),
            'approved_by_qr_code_content' => QrSignatureHelper::generateForMWT($report->id, Auth::user(), 'approved'),
        ]);

        return redirect()->back()->with('success', 'Laporan MWT berhasil disetujui.');
    }

    public function printRequest(MwtReport $report)
    {
        $report->load(['participants.user', 'acknowledger', 'approver']);
        return view('mwt_requests.print', compact('report'));
    }

    public function exportReport()
    {
        return Excel::download(new MwtReportExport, 'laporan-mwt.xlsx');
    }
}
