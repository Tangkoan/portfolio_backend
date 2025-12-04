<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity_log.index');
    }

    // API: ទាញយកទិន្នន័យ JSON
    public function fetchLogs(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('description', 'like', "%{$keyword}%")
                  ->orWhereHas('causer', function($userQuery) use ($keyword) {
                      $userQuery->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        $perPage = $request->input('per_page', 15);
        $logs = $query->paginate($perPage);

        // Transform Data: រៀបចំទិន្នន័យឱ្យស្អាតសម្រាប់ JS បង្ហាញ
        $logs->getCollection()->transform(function ($log) {
            return [
                'id' => $log->id,
                'causer_name' => $log->causer ? $log->causer->name : 'System',
                'causer_email' => $log->causer ? $log->causer->email : '',
                'causer_initial' => $log->causer ? substr($log->causer->name, 0, 2) : 'SY',
                'description' => $log->description,
                'subject_type' => $log->subject_type ? class_basename($log->subject_type) : '-',
                'subject_id' => $log->subject_id ?? '-',
                'created_at_date' => $log->created_at->format('d M Y, h:i A'),
                'created_at_ago' => $log->created_at->diffForHumans(),
                'badge_class' => $this->getBadgeClass($log->description),
                // Format Changes HTML នៅទីនេះតែម្តង ដើម្បីកុំអោយស្មុគស្មាញនៅ JS
                'changes_html' => $this->formatChanges($log) 
            ];
        });

        return response()->json($logs);
    }

    public function destroy($id)
    {
        $log = Activity::findOrFail($id);
        $log->delete();
        return response()->json(['message' => 'Log deleted successfully!']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Activity::whereIn('id', $request->ids)->delete();
        return response()->json(['message' => 'Selected logs deleted successfully!']);
    }

    // Helper: កំណត់ពណ៌ Badge
    private function getBadgeClass($description)
    {
        return match ($description) {
            'created' => 'bg-green-100 text-green-700',
            'updated' => 'bg-blue-100 text-blue-700',
            'deleted' => 'bg-red-100 text-red-700',
            'logged in' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    // Helper: បង្កើត HTML សម្រាប់បង្ហាញការផ្លាស់ប្តូរ (Diff)
    private function formatChanges($log)
    {
        if (!$log->properties || $log->properties->count() == 0) return '-';

        $html = '<div class="text-xs font-mono bg-page-bg/50 p-2 rounded border border-border-color">';

        if ($log->description == 'logged in') {
            $html .= '<p><span class="text-secondary">IP:</span> ' . ($log->properties['ip'] ?? '') . '</p>';
            $html .= '<p><span class="text-secondary">Browser:</span> ' . Str::limit($log->properties['browser'] ?? '', 30) . '</p>';
        } 
        elseif (isset($log->properties['old']) && isset($log->properties['attributes'])) {
            foreach ($log->properties['attributes'] as $key => $newValue) {
                if (isset($log->properties['old'][$key]) && $log->properties['old'][$key] != $newValue) {
                    $html .= '<div class="mb-1">';
                    $html .= '<span class="text-secondary">' . $key . ':</span> ';
                    $html .= '<span class="text-red-500 line-through mr-1">' . $log->properties['old'][$key] . '</span>';
                    $html .= '<i class="ri-arrow-right-line text-[10px] text-secondary mr-1"></i>';
                    $html .= '<span class="text-green-600 font-bold">' . $newValue . '</span>';
                    $html .= '</div>';
                }
            }
        } else {
            $html .= Str::limit(json_encode($log->properties), 100);
        }

        $html .= '</div>';
        return $html;
    }
}