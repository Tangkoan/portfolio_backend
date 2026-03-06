<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TechnoController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.techno.tectno_list');
    }

    public function fetch(Request $request)
    {
        $query = Technology::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $perPage = $request->input('per_page', 10);
        $data = ($perPage === 'all') ? $query->latest()->paginate(999999) : $query->latest()->paginate((int)$perPage);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'status' => 'required',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $path = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('technologies', 'public');
            }

            Technology::create([
                'name'   => $request->name,
                'status' => $request->status,
                'image'  => $path,
            ]);

            return response()->json(['status' => 'success', 'message' => __('messages.techno_created')]);
        });
    }

    public function update(Request $request, $id)
    {
        $techno = Technology::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'status' => 'required',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            if ($techno->image) Storage::disk('public')->delete($techno->image);
            $techno->image = $request->file('image')->store('technologies', 'public');
        }

        $techno->name = $request->name;
        $techno->status = $request->status;
        $techno->save();

        return response()->json(['status' => 'success', 'message' => __('messages.techno_updated')]);
    }

    public function destroy($id)
    {
        $techno = Technology::findOrFail($id);
        if ($techno->image) Storage::disk('public')->delete($techno->image);
        $techno->delete();

        return response()->json(['status' => 'success', 'message' => __('messages.techno_deleted')]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        $technos = Technology::whereIn('id', $ids)->get();
        
        foreach ($technos as $techno) {
            if ($techno->image) Storage::disk('public')->delete($techno->image);
            $techno->delete();
        }

        return response()->json(['status' => 'success', 'message' => __('messages.techno_deleted')]);
    }
}