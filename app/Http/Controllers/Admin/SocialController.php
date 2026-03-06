<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Social;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.socials.socials_list');
    }

    public function fetch(Request $request)
    {
        $query = Social::query();

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
            'name'       => 'required|string|max:255',
            'url_social' => 'required|url|max:255',
            'image' => 'nullable|image|max:2048',
            'status'     => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'url_social', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('socials', 'public');
        }

        Social::create($data);
        return response()->json(['message' => 'Social link created successfully']);
    }

    public function update(Request $request, $id)
    {
        $social = Social::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'url_social' => 'required|url|max:255',
            'image' => 'nullable|image|max:2048',
            'status'     => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $social->name = $request->name;
        $social->url_social = $request->url_social;
        $social->status = $request->status;

        if ($request->hasFile('image')) {
            if ($social->image) Storage::disk('public')->delete($social->image);
            $social->image = $request->file('image')->store('socials', 'public');
        }
        
        $social->save();

        return response()->json(['message' => 'Social link updated successfully']);
    }

    public function destroy($id)
    {
        $social = Social::findOrFail($id);
        if ($social->image) Storage::disk('public')->delete($social->image);
        $social->delete();
        return response()->json(['message' => 'Social link deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $social = Social::findOrFail($id);
        $social->status = $social->status ? 0 : 1;
        $social->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            $socials = Social::whereIn('id', $ids)->get();
            foreach($socials as $social) {
                if ($social->image) Storage::disk('public')->delete($social->image);
            }
            Social::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}