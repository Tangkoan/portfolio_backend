<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutMe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AboutMeController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.about_me.index');
    }

    public function fetch(Request $request)
    {
        $query = AboutMe::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'status'      => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('about_me', 'public');
        }

        AboutMe::create($data);
        return response()->json(['message' => 'Profile created successfully']);
    }

    public function update(Request $request, $id)
    {
        $aboutMe = AboutMe::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'status'      => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $aboutMe->name = $request->name;
        $aboutMe->description = $request->description;
        $aboutMe->status = $request->status;

        if ($request->hasFile('image')) {
            if ($aboutMe->image) Storage::disk('public')->delete($aboutMe->image);
            $aboutMe->image = $request->file('image')->store('about_me', 'public');
        }
        
        $aboutMe->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function destroy($id)
    {
        $aboutMe = AboutMe::findOrFail($id);
        if ($aboutMe->image) Storage::disk('public')->delete($aboutMe->image);
        $aboutMe->delete();
        return response()->json(['message' => 'Profile deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $aboutMe = AboutMe::findOrFail($id);
        $aboutMe->status = $aboutMe->status ? 0 : 1;
        $aboutMe->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            $items = AboutMe::whereIn('id', $ids)->get();
            foreach($items as $item) {
                if ($item->image) Storage::disk('public')->delete($item->image);
            }
            AboutMe::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected profiles deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}