<?php

namespace App\Http\Controllers;

use App\Models\DtlrDocumentReview;
use App\Models\DtlrDocument;
use Illuminate\Http\Request;

class DtlrDocumentReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = DtlrDocumentReview::with(['document', 'reviewer'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('document', function($q2) use ($search) {
                    $q2->where('tracking_number', 'like', "%{$search}%")
                       ->orWhere('title', 'like', "%{$search}%");
                })
                ->orWhere('comments', 'like', "%{$search}%");
            });
        }

        // Filter by review status
        if ($request->has('review_status') && $request->review_status != '') {
            $query->where('review_status', $request->review_status);
        }

        $reviews = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews,
            'stats' => [
                'total' => DtlrDocumentReview::count(),
                'pending' => DtlrDocumentReview::where('review_status', 'pending')->count(),
                'approved' => DtlrDocumentReview::where('review_status', 'approved')->count(),
                'rejected' => DtlrDocumentReview::where('review_status', 'rejected')->count(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:dtlr_documents,id',
            'reviewer_id' => 'required|exists:dtlr_users,id',
            'review_status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string'
        ]);

        try {
            $review = DtlrDocumentReview::create([
                'document_id' => $request->document_id,
                'reviewer_id' => $request->reviewer_id,
                'review_status' => $request->review_status,
                'comments' => $request->comments,
                'reviewed_at' => $request->review_status !== 'pending' ? now() : null
            ]);

            // Update document status based on review
            $document = DtlrDocument::find($request->document_id);
            if ($document && $request->review_status !== 'pending') {
                $document->update([
                    'status' => $request->review_status === 'approved' ? 'approved' : 'rejected',
                    'updated_by' => $request->reviewer_id
                ]);

                // Log the review action
                \App\Models\DtlrDocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'reviewed',
                    'from_branch_id' => $document->current_branch_id,
                    'performed_by' => $request->reviewer_id,
                    'notes' => 'Document reviewed: ' . $request->review_status . '. Comments: ' . ($request->comments ?? 'None'),
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $review = DtlrDocumentReview::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $request->validate([
            'review_status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string'
        ]);

        try {
            $review->update([
                'review_status' => $request->review_status,
                'comments' => $request->comments,
                'reviewed_at' => $request->review_status !== 'pending' ? now() : null
            ]);

            // Update document status based on review
            $document = DtlrDocument::find($review->document_id);
            if ($document && $request->review_status !== 'pending') {
                $document->update([
                    'status' => $request->review_status === 'approved' ? 'approved' : 'rejected',
                    'updated_by' => $review->reviewer_id
                ]);

                // Log the review update action
                \App\Models\DtlrDocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'reviewed',
                    'from_branch_id' => $document->current_branch_id,
                    'performed_by' => $review->reviewer_id,
                    'notes' => 'Review updated: ' . $request->review_status . '. Comments: ' . ($request->comments ?? 'None'),
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }
}