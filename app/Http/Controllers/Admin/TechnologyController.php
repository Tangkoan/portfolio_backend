<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Technology;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.technologies.technologies_list');
    }

    public function fetch(Request $request)
    {
        $query = Technology::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $allowedSort = ['id','name','created_at','status'];

        $sortBy  = in_array($request->sort_by, $allowedSort) ? $request->sort_by : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 10);

        return response()->json($query->paginate((int)$perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'status' => 1
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('technologies', 'public');
        }

        Technology::create($data);
        return response()->json(['message' => 'Technology created successfully']);
    }

    public function update(Request $request, $id)
    {
        $tech = Technology::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $tech->name = $request->name;
        if ($request->hasFile('image')) {
            if ($tech->image) Storage::disk('public')->delete($tech->image);
            $tech->image = $request->file('image')->store('technologies', 'public');
        }
        $tech->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        $tech = Technology::findOrFail($id);
        if ($tech->image) Storage::disk('public')->delete($tech->image);
        $tech->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $tech = Technology::findOrFail($id);
        $tech->status = $tech->status ? 0 : 1;
        $tech->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // expect array of IDs
            if(!empty($ids)){
                Technology::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => 'Selected technologies deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'No items selected']);
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->ids; // array of IDs
        $status = $request->status; // new status
        if(!empty($ids)){
            Technology::whereIn('id', $ids)->update(['status' => $status]);
            return response()->json(['success' => true, 'message' => 'Selected technologies updated successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}