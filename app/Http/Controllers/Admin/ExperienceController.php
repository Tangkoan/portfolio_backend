<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.experiences');
    }

    public function fetchExperiences(Request $request)
    {
        $query = Experience::query();

        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('sup_name', 'like', '%' . $request->keyword . '%');
            });
        }

        $sortBy = $request->input('sort_by', 'start_day');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 10);
        $experiences = ($perPage === 'all') ? $query->paginate(999999) : $query->paginate((int)$perPage);

        return response()->json($experiences);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'sup_name'  => 'required|string|max:255',
            'start_day' => 'required|date',
            'end_day'   => 'nullable|date|after_or_equal:start_day',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $experience = Experience::create($request->all());
            return response()->json(['status' => 'success', 'message' => 'Experience created successfully']);
        });
    }

    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'sup_name'  => 'required|string|max:255',
            'start_day' => 'required|date',
            'end_day'   => 'nullable|date|after_or_equal:start_day',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $experience->update($request->all());

        return response()->json(['status' => 'success', 'message' => 'Experience updated successfully']);
    }

    public function destroy($id)
    {
        Experience::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        Experience::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'success', 'message' => 'Items deleted successfully']);
    }
}