<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message.
     */
    public function store(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service_type' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Save contact message to database
            $contactMessage = ContactMessage::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Contact message saved successfully',
                'data' => $contactMessage,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save contact message',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
