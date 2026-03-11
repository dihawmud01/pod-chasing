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
        $query = Prospect::orderBy('eta')->orderBy('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('eta_date')) {
            $query->whereDate('eta', $request->eta_date);
        }

        $prospects = $query->get();
        $statuses  = Prospect::$statuses;
        $etaDate   = $request->get('eta_date', '');

        return view('prospects.index', compact('prospects', 'statuses', 'etaDate'));
    }

    public function create()
    {
        $statuses = Prospect::$statuses;
        return view('prospects.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vessel_name' => 'required|string|max:255',
            'status'      => 'required|in:' . implode(',', array_keys(Prospect::$statuses)),
        ]);

        Prospect::create([
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

        return redirect()->route('prospects.index')
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
            'vessel_name' => 'required|string|max:255',
            'status'      => 'required|in:' . implode(',', array_keys(Prospect::$statuses)),
        ]);

        $prospect->update([
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

        return redirect()->route('prospects.index')
            ->with('success', 'Prospect updated successfully!');
    }

    public function destroy(Prospect $prospect)
    {
        $prospect->delete();
        return redirect()->route('prospects.index')
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
     * Export filtered prospects list as a PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Prospect::orderBy('eta')->orderBy('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('eta_date')) {
            $query->whereDate('eta', $request->eta_date);
        }

        $prospects   = $query->get();
        $statuses    = Prospect::$statuses;
        $etaDate     = $request->get('eta_date', '');

        $filterParts = [];
        if ($request->filled('status')) {
            $filterParts[] = 'Status: ' . ($statuses[$request->status] ?? ucfirst($request->status));
        }
        if ($etaDate) {
            $filterParts[] = 'ETA: ' . \Carbon\Carbon::parse($etaDate)->format('d M Y');
        }
        $filterLabel = $filterParts ? implode(' | ', $filterParts) : 'All';

        $pdf = Pdf::loadView('prospects.pdf', compact('prospects', 'statuses', 'filterLabel', 'etaDate'))
            ->setPaper('a4', 'landscape');

        $filename = 'prospects-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
