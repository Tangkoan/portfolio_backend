<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.portfolio.projects.projects_list');
    }

    public function fetch(Request $request)
    {
        $query = Project::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('sup_name', 'like', '%' . $request->keyword . '%');
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
            'sup_name'    => 'nullable|string|max:255',
            'url_project' => 'nullable|url|max:255',
            'image'       => 'nullable|image|max:2048',
            'status'      => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'sup_name', 'url_project', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($data);
        return response()->json(['message' => 'Project created successfully']);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'sup_name'    => 'nullable|string|max:255',
            'url_project' => 'nullable|url|max:255',
            'image'       => 'nullable|image|max:2048',
            'status'      => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $project->name = $request->name;
        $project->sup_name = $request->sup_name;
        $project->url_project = $request->url_project;
        $project->status = $request->status;

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $project->image = $request->file('image')->store('projects', 'public');
        }
        
        $project->save();

        return response()->json(['message' => 'Project updated successfully']);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        if ($project->image) Storage::disk('public')->delete($project->image);
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $project = Project::findOrFail($id);
        $project->status = $project->status ? 0 : 1;
        $project->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)){
            $projects = Project::whereIn('id', $ids)->get();
            foreach($projects as $project) {
                if ($project->image) Storage::disk('public')->delete($project->image);
            }
            Project::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected projects deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No items selected']);
    }
}