<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Facility;
use App\Models\AccessLog;

class MemberController extends Controller
{
    public function addMember(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'membership_type' => 'required|string|in:BASIC,VIP',
            'valid_until' => 'required|date|after:today',
        ]);

        // Tambahkan member baru
        $member = Member::create([
            'name' => $request->name,
            'membership_type' => $request->membership_type,
            'valid_until' => $request->valid_until,
        ]);

        return response()->json([
            'message' => 'Member added successfully',
            'member' => $member,
        ], 201);
    }

    public function listMembers(Request $request)
    {
        // Mendapatkan daftar member dengan pagination
        $members = Member::select('id', 'name', 'membership_type', 'valid_until')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return response()->json([
            'message' => 'List of members retrieved successfully',
            'members' => $members,
        ], 200);
    }

    public function addFacility(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name',
        ]);

        // Tambahkan fasilitas baru
        $facility = Facility::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Facility added successfully',
            'facility' => $facility,
        ], 201);
    }

    public function listFacility(Request $request)
    {
        // Mendapatkan daftar fasilitas
        $facilities = Facility::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'message' => 'List of facilities retrieved successfully',
            'facilities' => $facilities,
        ], 200);
    }
    
    public function checkIn(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        // Cari anggota berdasarkan ID
        $member = Member::find($request->member_id);

        // Simpan log akses
        AccessLog::create([
            'member_id' => $member->id,
            'facility_id' => $request->facility_id,
            'access_time' => now(),
        ]);

        return response()->json(['message' => 'Check-in recorded successfully'], 200);
    }

    public function accessFacility(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        // Cari anggota berdasarkan ID
        $member = Member::find($request->member_id);

        // Periksa apakah keanggotaan masih aktif
        if ($member->valid_until < now()) {
            return response()->json(['message' => 'Membership expired'], 403);
        }

        // Periksa apakah fasilitas ada
        $facility = Facility::find($request->facility_id);

        // Berikan akses
        return response()->json([
            'message' => 'Access granted',
            'member' => [
                'name' => $member->name,
                'member_id' => $member->id,
                'membership_type' => $member->membership_type,
                'valid_until' => $member->valid_until,
            ],
            'facility' => $facility->name,
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        // Cari anggota berdasarkan ID
        $member = Member::find($request->member_id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        // Cari log akses terakhir anggota di fasilitas yang dimaksud
        $lastLog = AccessLog::where('member_id', $member->id)
            ->where('facility_id', $request->facility_id)
            ->orderBy('access_time', 'desc')
            ->first();

        if (!$lastLog) {
            return response()->json(['message' => 'No access log found for this member at this facility'], 404);
        }

        // Periksa apakah anggota sudah check-out
        if ($lastLog->check_out_time) {
            return response()->json(['message' => 'Member has already checked out from this facility'], 403);
        }

        // Perbarui waktu check-out
        $lastLog->update(['check_out_time' => now()]);

        return response()->json([
            'message' => 'Check-out recorded successfully',
            'member' => [
                'name' => $member->name,
                'member_id' => $member->id,
            ],
            'facility' => Facility::find($request->facility_id)->name,
            'check_out_time' => $lastLog->check_out_time,
        ]);
    }

    public function membershipStatus(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // Cari anggota berdasarkan ID
        $member = Member::find($request->member_id);

        // Periksa status keanggotaan
        $status = $member->valid_until >= now() ? 'Active' : 'Expired';

        return response()->json([
            'message' => 'Membership status retrieved successfully',
            'member' => [
                'name' => $member->name,
                'member_id' => $member->id,
                'membership_type' => $member->membership_type,
                'valid_until' => $member->valid_until,
                'status' => $status,
            ],
        ], 200);
    }
}