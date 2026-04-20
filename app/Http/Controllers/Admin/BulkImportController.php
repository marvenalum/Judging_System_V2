<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\ScoresImport;
use App\Exports\ScoresExport;

class BulkImportController extends Controller
{
    public function index()
    {
$events = Event::where('event_status', 'ongoing')->get();
        return view('admin.bulk-import.index', compact('events'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_id' => 'nullable|exists:categories,id',
            'format' => 'required|in:csv',
        ]);

        $event = Event::findOrFail($request->event_id);
        $category = $request->category_id ? Category::findOrFail($request->category_id) : null;

        $export = new ScoresExport($request->event_id, $request->category_id);
        $csvContent = $export->generateCsv();

        $filename = 'scores_' . $event->event_name;
        if ($category) {
            $filename .= '_' . $category->name;
        }
        $filename .= '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'event_id' => 'required|exists:events,id',
            'category_id' => 'nullable|exists:categories,id',
            'import_mode' => 'required|in:create,update,upsert',
        ]);

        $event = Event::findOrFail($request->event_id);
        $category = $request->category_id ? Category::findOrFail($request->category_id) : null;

        try {
            DB::beginTransaction();

            $csvContent = file_get_contents($request->file->getRealPath());

            $import = new ScoresImport(
                $request->event_id,
                $request->category_id,
                $request->import_mode
            );

            $results = $import->importFromCsv($csvContent);

            DB::commit();

            $errors = $import->getErrors();
            $errorMessage = !empty($errors) ? "\n\nErrors:\n" . implode("\n", array_slice($errors, 0, 10)) : '';

            return redirect()->back()->with('success',
                "Import completed successfully! " .
                "Created: {$results['created']}, " .
                "Updated: {$results['updated']}, " .
                "Skipped: {$results['skipped']}, " .
                "Errors: {$results['errors']}" .
                $errorMessage
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk import failed: ' . $e->getMessage());

            return redirect()->back()->with('error',
                'Import failed: ' . $e->getMessage()
            );
        }
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $category = $request->category_id ? Category::findOrFail($request->category_id) : null;

        $export = new ScoresExport($request->event_id, $request->category_id, true);
        $csvContent = $export->generateCsv();

        $filename = 'scores_template_' . $event->event_name;
        if ($category) {
            $filename .= '_' . $category->name;
        }
        $filename .= '_' . now()->format('Y-m-d') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function getCategories(Request $request)
    {
        $eventId = $request->query('event_id');
        $categories = Category::where('event_id', $eventId)->where('status', 'active')->get();

        return response()->json($categories);
    }

    public function getCriteria(Request $request)
    {
        $categoryId = $request->query('category_id');
        $criteria = Criteria::where('category_id', $categoryId)->where('status', 'active')->get();

        return response()->json($criteria);
    }
}
