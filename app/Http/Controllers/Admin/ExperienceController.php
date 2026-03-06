<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience; // កុំភ្លេចបង្កើត Model នេះ
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.experiences.experiences_list');
    }

    public function fetch(Request $request)
    {
        $query = Experience::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('sup_name', 'like', '%' . $request->keyword . '%');
        }

        $allowedSort = ['id', 'name', 'sup_name', 'start_day', 'created_at', 'status'];
        $sortBy  = in_array($request->sort_by, $allowedSort) ? $request->sort_by : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);
        $perPage = $request->input('per_page', 10);

        return response()->json($query->paginate((int)$perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'sup_name'  => 'required|string|max:255',
            'start_day' => 'required|date',
            'end_day'   => 'nullable|date|after_or_equal:start_day',
            'status'    => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Experience::create($request->only(['name', 'sup_name', 'start_day', 'end_day', 'status']));
        return response()->json(['message' => 'Experience created successfully']);
    }

    public function update(Request $request, $id)
    {
        $exp = Experience::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'sup_name'  => 'required|string|max:255',
            'start_day' => 'required|date',
            'end_day'   => 'nullable|date|after_or_equal:start_day',
            'status'    => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $exp->update($request->only(['name', 'sup_name', 'start_day', 'end_day', 'status']));
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        Experience::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $exp = Experience::findOrFail($id);
        $exp->status = $exp->status ? 0 : 1;
        $exp->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            Experience::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected experiences deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}