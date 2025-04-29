<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\PtppRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function showUserRequests()
    {
        $userId = Auth::id();

        $myRequests = PtppRequest::where('from_user_id', $userId)->get();
        $requestsToMe = PtppRequest::where('to_user_id', $userId)->get();

        return view('requests.index', compact('myRequests', 'requestsToMe'));
    }

    public function showCreate()
    {
        $users = User::where('role', '!=', 'ITM')->get();
        return view('requests.create', compact('users'));
    }

    public function handleCreate(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'area_location' => 'nullable|string',
            'source_of_nonconformity' => 'nullable|array',
            'nonconformity_description' => 'required|string',
            'requirement_violated' => 'nullable|string',
            'category' => 'nullable|in:Temuan,Observasi',
            'due_date' => 'nullable|date',
            'illustration_photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('illustration_photo')) {
            $photoPath = $request->file('illustration_photo')->store('illustrations', 'public');
        }

        PtppRequest::create([
            'no_form' => 'PND646000/' . now()->format('Y'),
            'request_date' => now(),
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'area_location' => $request->area_location,
            'source_of_nonconformity' => json_encode($request->source_of_nonconformity),
            'nonconformity_description' => $request->nonconformity_description,
            'requirement_violated' => $request->requirement_violated,
            'category' => $request->category,
            'due_date' => $request->due_date,
            'illustration_photo_path' => $photoPath,
            'status' => 'waiting_itm_initial_review',
        ]);

        return redirect()->route('requests.show')->with('success', 'Request berhasil dikirim, menunggu ITM review.');
    }
}
