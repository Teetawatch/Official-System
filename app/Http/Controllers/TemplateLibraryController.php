<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateLibraryController extends Controller
{
    /**
     * Admin: Display list of all templates with management options
     */
    public function adminIndex(Request $request)
    {
        $query = DocumentTemplate::with('uploader')
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $templates = $query->paginate(12)->withQueryString();
        $categories = DocumentTemplate::getCategories();

        // Statistics
        $stats = [
            'total' => DocumentTemplate::count(),
            'active' => DocumentTemplate::active()->count(),
            'featured' => DocumentTemplate::featured()->count(),
            'total_downloads' => DocumentTemplate::sum('download_count'),
        ];

        return view('typing.admin.templates.index', compact('templates', 'categories', 'stats'));
    }

    /**
     * Admin: Show form to create new template
     */
    public function create()
    {
        $categories = DocumentTemplate::getCategories();
        return view('typing.admin.templates.create', compact('categories'));
    }

    /**
     * Admin: Store new template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string',
            'file' => 'required|file|mimes:docx,doc,pdf,odt|max:10240', // 10MB max
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
        ]);

        // Handle file upload
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        // Generate unique file path
        $storagePath = 'templates/' . Str::uuid() . '.' . $fileExtension;
        Storage::disk('uploads')->put($storagePath, file_get_contents($file));

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = 'templates/thumbnails/' . Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
            Storage::disk('uploads')->put($thumbnailPath, file_get_contents($thumbnail));
        }

        DocumentTemplate::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'file_path' => $storagePath,
            'file_name' => $fileName,
            'file_type' => $fileExtension,
            'file_size' => $fileSize,
            'thumbnail' => $thumbnailPath,
            'uploaded_by' => auth()->id(),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => true,
        ]);

        return redirect()->route('typing.admin.templates.index')
            ->with('success', 'อัปโหลดเอกสารตัวอย่างสำเร็จ');
    }

    /**
     * Admin: Show form to edit template
     */
    public function edit($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $categories = DocumentTemplate::getCategories();
        return view('typing.admin.templates.edit', compact('template', 'categories'));
    }

    /**
     * Admin: Update template
     */
    public function update(Request $request, $id)
    {
        $template = DocumentTemplate::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string',
            'file' => 'nullable|file|mimes:docx,doc,pdf,odt|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $template->title = $validated['title'];
        $template->description = $validated['description'] ?? null;
        $template->category = $validated['category'];
        $template->is_featured = $request->boolean('is_featured');
        $template->is_active = $request->boolean('is_active');

        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($template->file_path && Storage::disk('uploads')->exists($template->file_path)) {
                Storage::disk('uploads')->delete($template->file_path);
            }

            $file = $request->file('file');
            $storagePath = 'templates/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('uploads')->put($storagePath, file_get_contents($file));

            $template->file_path = $storagePath;
            $template->file_name = $file->getClientOriginalName();
            $template->file_type = $file->getClientOriginalExtension();
            $template->file_size = $file->getSize();
        }

        // Handle new thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($template->thumbnail && Storage::disk('uploads')->exists($template->thumbnail)) {
                Storage::disk('uploads')->delete($template->thumbnail);
            }

            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = 'templates/thumbnails/' . Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
            Storage::disk('uploads')->put($thumbnailPath, file_get_contents($thumbnail));
            $template->thumbnail = $thumbnailPath;
        }

        $template->save();

        return redirect()->route('typing.admin.templates.index')
            ->with('success', 'อัปเดตเอกสารตัวอย่างสำเร็จ');
    }

    /**
     * Admin: Delete template
     */
    public function destroy($id)
    {
        $template = DocumentTemplate::findOrFail($id);

        // Delete files
        if ($template->file_path && Storage::disk('uploads')->exists($template->file_path)) {
            Storage::disk('uploads')->delete($template->file_path);
        }
        if ($template->thumbnail && Storage::disk('uploads')->exists($template->thumbnail)) {
            Storage::disk('uploads')->delete($template->thumbnail);
        }

        $template->delete();

        return redirect()->route('typing.admin.templates.index')
            ->with('success', 'ลบเอกสารตัวอย่างสำเร็จ');
    }

    /**
     * Student: Display template library
     */
    public function studentIndex(Request $request)
    {
        $query = DocumentTemplate::active()
            ->with('uploader')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $templates = $query->paginate(12)->withQueryString();
        $categories = DocumentTemplate::getCategories();
        $featuredTemplates = DocumentTemplate::active()->featured()->take(3)->get();

        return view('typing.student.templates.index', compact('templates', 'categories', 'featuredTemplates'));
    }

    /**
     * Student: View template details
     */
    public function show($id)
    {
        $template = DocumentTemplate::active()->with('uploader')->findOrFail($id);
        $template->incrementView();

        // Get related templates (same category)
        $relatedTemplates = DocumentTemplate::active()
            ->where('category', $template->category)
            ->where('id', '!=', $template->id)
            ->take(4)
            ->get();

        return view('typing.student.templates.show', compact('template', 'relatedTemplates'));
    }

    /**
     * Download template file
     */
    public function download($id)
    {
        $template = DocumentTemplate::findOrFail($id);

        // Check if student and template is not active
        if (auth()->user()->role === 'student' && !$template->is_active) {
            abort(404);
        }

        $template->incrementDownload();

        $filePath = public_path('uploads/' . $template->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'ไม่พบไฟล์');
        }

        return response()->download($filePath, $template->file_name);
    }

    /**
     * Admin: Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $template->is_featured = !$template->is_featured;
        $template->save();

        return response()->json([
            'success' => true,
            'is_featured' => $template->is_featured
        ]);
    }

    /**
     * Admin: Toggle active status
     */
    public function toggleActive($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $template->is_active = !$template->is_active;
        $template->save();

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active
        ]);
    }
}
