<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Contact;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Menampilkan Landing Page dengan Data Indikator di-inject langsung
     */
    public function index()
    {
        // Mengambil struktur domain, subdomain, dan indikator dari DB
        $domains = Domain::with('subdomains.indicators')->get();

        return view('landing', compact('domains'));
    }

    /**
     * Menangani submit assessment via standard POST (Bukan API)
     */
    public function submitAssessment(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Simpan Header Assessment
            $assessment = Assessment::create([
                'org_name' => $request->org_name,
                'org_type' => $request->org_type,
                'assessor_name' => $request->assessor_name,
                'assessor_position' => $request->assessor_position,
                'total_score' => $request->total_score,
                'status' => 'completed'
            ]);

            // 2. Simpan setiap jawaban indikator
            foreach ($request->responses as $indicatorId => $data) {
                $assessment->responses()->create([
                    'indicator_id' => $indicatorId,
                    'score' => $data['score'],
                    'evidence_text' => $data['evidence'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('landing')->with('success', 'Assessment berhasil disimpan dengan ID: ' . $assessment->id);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menangani submit contact form via standard POST (Bukan API)
     */
    public function submitContact(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Simpan data kontak ke database atau kirim email
        Contact::create($request->only('name', 'email', 'message'));

        return redirect()->route('landing')->with('success', 'Pesan Anda telah terkirim. Terima kasih!');
    }
}
