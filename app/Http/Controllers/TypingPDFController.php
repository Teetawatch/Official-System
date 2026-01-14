<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypingSubmission;
use PDF; // Alias for Barryvdh\DomPDF\Facade\Pdf

class TypingPDFController extends Controller
{
    public function download(Request $request, $id)
    {
        $submission = TypingSubmission::with(['user', 'assignment'])->findOrFail($id);
        
        // Authorization check (optional but good practice)
        // if ($request->user()->role !== 'admin' && $request->user()->id !== $submission->user_id) {
        //    abort(403);
        // }

        $pdf = PDF::loadView('typing.pdf.document', compact('submission'));
        
        // Setup paper size and orientation (A4 Portrait usually for documents)
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('submission_' . $submission->id . '.pdf');
    }
}
