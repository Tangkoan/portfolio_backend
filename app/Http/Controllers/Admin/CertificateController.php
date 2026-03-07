<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.certificates.index');
    }

    public function fetch(Request $request)
    {
        $query = Certificate::query();

        // ស្វែងរកតាម ID បើមានបញ្ចូលលេខ
        if ($request->keyword) {
            $query->where('id', $request->keyword);
        }

        $allowedSort = ['id', 'created_at', 'status'];
        $sortBy  = in_array($request->sort_by, $allowedSort) ? $request->sort_by : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);
        $perPage = $request->input('per_page', 10);

        return response()->json($query->paginate((int)$perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'  => 'required|image|max:3072', // តម្រូវឲ្យមានរូបភាពពេលបង្កើតថ្មី
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = ['status' => $request->status];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('certificates', 'public');
        }

        Certificate::create($data);
        return response()->json(['message' => 'Certificate uploaded successfully']);
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'image'  => 'nullable|image|max:3072', // អាចអត់រូបក៏បាន ពេល Update
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $certificate->status = $request->status;

        if ($request->hasFile('image')) {
            if ($certificate->image) Storage::disk('public')->delete($certificate->image);
            $certificate->image = $request->file('image')->store('certificates', 'public');
        }
        
        $certificate->save();

        return response()->json(['message' => 'Certificate updated successfully']);
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        if ($certificate->image) Storage::disk('public')->delete($certificate->image);
        $certificate->delete();
        return response()->json(['message' => 'Certificate deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = $certificate->status ? 0 : 1;
        $certificate->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            $items = Certificate::whereIn('id', $ids)->get();
            foreach($items as $item) {
                if ($item->image) Storage::disk('public')->delete($item->image);
            }
            Certificate::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected certificates deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}