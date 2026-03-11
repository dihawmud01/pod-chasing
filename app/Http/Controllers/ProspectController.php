<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\Vessel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $date  = $request->get('date', now()->toDateString());
        $query = Prospect::whereDate('prospect_date', $date)->orderBy('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospects = $query->get();
        $statuses  = Prospect::$statuses;

        return view('prospects.index', compact('prospects', 'statuses', 'date'));
    }

    public function create(Request $request)
    {
        $statuses      = Prospect::$statuses;
        $prospectDate  = $request->get('date', now()->toDateString());
        return view('prospects.create', compact('statuses', 'prospectDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vessel_name'  => 'required|string|max:255',
            'status'       => 'required|in:' . implode(',', array_keys(Prospect::$statuses)),
            'prospect_date'=> 'required|date',
        ]);

        Prospect::create([
            'prospect_date'       => $request->prospect_date,
            'vessel_name'         => $request->vessel_name,
            'port'                => $request->port,
            'eta'                 => $request->eta ?: null,
            'etb'                 => $request->etb ?: null,
            'etd'                 => $request->etd ?: null,
            'destination_country' => $request->destination_country,
            'forwarder'           => $request->forwarder,
            'delivery_date'       => $request->delivery_date ?: null,
            'status'              => $request->status,
            'notes'               => $request->notes,
        ]);

        return redirect()->route('prospects.index', ['date' => $request->prospect_date])
            ->with('success', 'Prospect added successfully!');
    }

    public function edit(Prospect $prospect)
    {
        $statuses = Prospect::$statuses;
        return view('prospects.edit', compact('prospect', 'statuses'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        $request->validate([
            'vessel_name'  => 'required|string|max:255',
            'status'       => 'required|in:' . implode(',', array_keys(Prospect::$statuses)),
            'prospect_date'=> 'required|date',
        ]);

        $prospect->update([
            'prospect_date'       => $request->prospect_date,
            'vessel_name'         => $request->vessel_name,
            'port'                => $request->port,
            'eta'                 => $request->eta ?: null,
            'etb'                 => $request->etb ?: null,
            'etd'                 => $request->etd ?: null,
            'destination_country' => $request->destination_country,
            'forwarder'           => $request->forwarder,
            'delivery_date'       => $request->delivery_date ?: null,
            'status'              => $request->status,
            'notes'               => $request->notes,
        ]);

        return redirect()->route('prospects.index', ['date' => $request->prospect_date])
            ->with('success', 'Prospect updated successfully!');
    }

    public function destroy(Prospect $prospect)
    {
        $date = $prospect->prospect_date->toDateString();
        $prospect->delete();
        return redirect()->route('prospects.index', ['date' => $date])
            ->with('success', 'Prospect deleted successfully!');
    }

    /**
     * Create a Delivery (Vessel) record from this Prospect.
     * Does NOT alter the existing vessels table structure.
     */
    public function createDelivery(Prospect $prospect)
    {
        Vessel::create([
            'vessel_name'      => $prospect->vessel_name,
            'delivery_address' => $prospect->destination_country,
            'driver'           => $prospect->forwarder,
            'information'      => null,
            'customs_doc'      => false,
            'print_status'     => false,
            'delivered'        => false,
            'pod_status'       => false,
            'report_date'      => $prospect->delivery_date ?? now()->toDateString(),
        ]);

        $prospect->update(['status' => 'completed']);

        return redirect()->route('vessels.index', [
            'date' => ($prospect->delivery_date ?? now())->toDateString(),
        ])->with('success', 'Delivery created from prospect: ' . $prospect->vessel_name);
    }

    /**
     * Export prospects list as PDF (respects date + status filter).
     */
    public function exportPdf(Request $request)
    {
        $date  = $request->get('date', now()->toDateString());
        $query = Prospect::whereDate('prospect_date', $date)->orderBy('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospects = $query->get();
        $statuses  = Prospect::$statuses;

        $filterParts = [\Carbon\Carbon::parse($date)->format('d M Y')];
        if ($request->filled('status')) {
            $filterParts[] = 'Status: ' . ($statuses[$request->status] ?? ucfirst($request->status));
        }
        $filterLabel = implode(' | ', $filterParts);

        $pdf = Pdf::loadView('prospects.pdf', compact('prospects', 'statuses', 'filterLabel', 'date'))
            ->setPaper('a4', 'landscape');

        $filename = 'prospects-' . $date . '.pdf';

        return $pdf->download($filename);
    }
}
