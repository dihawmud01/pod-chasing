<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VesselController extends Controller
{
    public function index(Request $request)
    {
        $date    = $request->get('date', now()->toDateString());
        $vessels = Vessel::where('report_date', $date)->orderBy('id')->get();
        $drivers = Vessel::whereNotNull('driver')
                         ->where('driver', '!=', '')
                         ->distinct()
                         ->orderBy('driver')
                         ->pluck('driver');
        return view('vessels.index', compact('vessels', 'date', 'drivers'));
    }

    private function resolveInformation(Request $request): ?string
    {
        $type = $request->input('information_type');
        if ($type === '__other__') {
            return $request->input('information_custom') ?: null;
        }
        return $type ?: null;
    }

    public function store(Request $request)
    {
        $request->validate(['vessel_name' => 'required|string|max:255']);

        Vessel::create([
            'vessel_name'      => $request->vessel_name,
            'driver'           => $request->driver,
            'delivery_address' => $request->delivery_address,
            'information'      => $this->resolveInformation($request),
            'customs_doc'      => $request->boolean('customs_doc'),
            'print_status'     => $request->boolean('print_status'),
            'delivered'        => $request->boolean('delivered'),
            'pod_status'       => $request->boolean('pod_status'),
            'report_date'      => $request->report_date ?? now()->toDateString(),
        ]);

        return redirect()->route('vessels.index', ['date' => $request->report_date ?? now()->toDateString()])
            ->with('success', 'Vessel berhasil ditambahkan!');
    }

    public function update(Request $request, Vessel $vessel)
    {
        $request->validate(['vessel_name' => 'required|string|max:255']);

        $vessel->update([
            'vessel_name'      => $request->vessel_name,
            'driver'           => $request->driver,
            'delivery_address' => $request->delivery_address,
            'information'      => $this->resolveInformation($request),
            'customs_doc'      => $request->boolean('customs_doc'),
            'print_status'     => $request->boolean('print_status'),
            'delivered'        => $request->boolean('delivered'),
            'pod_status'       => $request->boolean('pod_status'),
        ]);

        return redirect()->route('vessels.index', ['date' => $vessel->report_date->toDateString()])
            ->with('success', 'Data vessel berhasil diupdate!');
    }

    public function destroy(Vessel $vessel)
    {
        $date = $vessel->report_date->toDateString();
        if ($vessel->pod_file) {
            Storage::disk('public')->delete('pod_files/' . $vessel->pod_file);
        }
        $vessel->delete();
        return redirect()->route('vessels.index', ['date' => $date])
            ->with('success', 'Vessel berhasil dihapus!');
    }

    public function quickUpdate(Request $request, Vessel $vessel)
    {
        $allowed = ['delivered', 'pod_status', 'customs_doc', 'print_status', 'driver', 'information', 'vessel_name', 'delivery_address'];
        $data = $request->only($allowed);

        foreach (['delivered', 'pod_status', 'customs_doc', 'print_status'] as $bool) {
            if (array_key_exists($bool, $data)) {
                $data[$bool] = filter_var($data[$bool], FILTER_VALIDATE_BOOLEAN);
            }
        }

        $vessel->update($data);

        return response()->json([
            'success' => true,
            'vessel'  => $vessel->fresh(),
        ]);
    }

    public function uploadPod(Request $request, Vessel $vessel)
    {
        $request->validate([
            'pod' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($vessel->pod_file) {
            Storage::disk('public')->delete('pod_files/' . $vessel->pod_file);
        }

        $filename = time() . '_' . $request->file('pod')->getClientOriginalName();
        $request->file('pod')->storeAs('pod_files', $filename, 'public');

        $vessel->update([
            'pod_file'   => $filename,
            'pod_status' => true,
        ]);

        return redirect()->route('vessels.index', ['date' => $vessel->report_date->toDateString()])
            ->with('success', 'POD file berhasil diupload!');
    }

    public function print(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $vessels = Vessel::where('report_date', $date)->orderBy('id')->get();
        return view('vessels.print', compact('vessels', 'date'));
    }
}
