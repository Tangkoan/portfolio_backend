<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.tools.tools_list');
    }

    public function fetch(Request $request)
    {
        $query = Tool::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $allowedSort = ['id', 'name', 'created_at', 'status'];
        $sortBy  = in_array($request->sort_by, $allowedSort) ? $request->sort_by : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);
        $perPage = $request->input('per_page', 10);

        return response()->json($query->paginate((int)$perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tools', 'public');
        }

        Tool::create($data);
        return response()->json(['message' => 'Tool created successfully']);
    }

    public function update(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $tool->name = $request->name;
        $tool->status = $request->status;

        if ($request->hasFile('image')) {
            if ($tool->image) Storage::disk('public')->delete($tool->image);
            $tool->image = $request->file('image')->store('tools', 'public');
        }
        
        $tool->save();

        return response()->json(['message' => 'Tool updated successfully']);
    }

    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        if ($tool->image) Storage::disk('public')->delete($tool->image);
        $tool->delete();
        return response()->json(['message' => 'Tool deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $tool = Tool::findOrFail($id);
        $tool->status = $tool->status ? 0 : 1;
        $tool->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            $tools = Tool::whereIn('id', $ids)->get();
            foreach($tools as $tool) {
                if ($tool->image) Storage::disk('public')->delete($tool->image);
            }
            Tool::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected tools deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}