<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Call;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    //     $this->middleware(['permission:view reports'])->only(['index', 'show']);
    //     $this->middleware(['permission:create reports'])->only(['create', 'store']);
    //     $this->middleware(['permission:edit reports'])->only(['edit', 'update']);
    //     $this->middleware(['permission:delete reports'])->only(['destroy']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agents = User::whereHas('roles', function($query) {
            $query->where('name', 'agent');
        })->get();
        
        return view('reports.create', compact('agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:call_report,ticket_report,agent_performance',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'filters' => 'nullable|array',
            'columns' => 'required|array'
        ]);

        $report = new Report($validated);
        $report->created_by = Auth::id();
        $report->status = 'pending';
        $report->save();

        // Process report data based on type
        $result = $this->generateReport($report);

        $report->result = $result;
        $report->status = 'completed';
        $report->save();

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report generated successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = Report::findOrFail($id);
        return view('reports.show', compact('report'));
    }

    /**
     * Generate report data based on report type.
     */
    protected function generateReport(Report $report)
    {
        $query = match($report->type) {
            'call_report' => Call::query(),
            'ticket_report' => Ticket::query(),
            default => null
        };

        if (!$query) {
            return [];
        }

        $query->whereBetween('created_at', [
            Carbon::parse($report->start_date),
            Carbon::parse($report->end_date)
        ]);

        // Apply filters
        if ($report->filters) {
            foreach ($report->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get data based on selected columns
        return $query->get($report->columns ?? ['*'])->toArray();
    }

    /**
     * Export the specified resource.
     */
    public function export(string $id)
    {
        $report = Report::findOrFail($id);

        // Generate CSV or Excel file based on report data
        $filename = Str::slug($report->title) . '_' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function() use ($report) {
            $output = fopen('php://output', 'w');

            // Add headers
            fputcsv($output, array_keys($report->columns));

            // Add data rows
            foreach ($report->result as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename);
    }
}
