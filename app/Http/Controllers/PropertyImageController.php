<?php

namespace App\Http\Controllers;

use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyImageController extends Controller
{
    // Thêm ảnh mới cho property
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $images = [];
        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('properties', 'public');
            $image = PropertyImage::create([
                'property_id' => $propertyId,
                'image_path' => $path,
                'image_name' => $file->getClientOriginalName(),
                'is_primary' => false,
                'sort_order' => $request->sort_order ?? 0
            ]);
            $images[] = $image;
        }

        return response()->json($images, 201);
    }

    // Xóa ảnh
    public function destroy($id)
    {
        $image = PropertyImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    // Đặt ảnh chính
    public function setPrimary($id)
    {
        $image = PropertyImage::findOrFail($id);

        // Reset tất cả ảnh của property này
        PropertyImage::where('property_id', $image->property_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return response()->json(['message' => 'Primary image set successfully']);
    }

    // Cập nhật sort_order
    public function updateSort(Request $request, $id)
    {
        $request->validate([
            'sort_order' => 'required|integer'
        ]);

        $image = PropertyImage::findOrFail($id);
        $image->update(['sort_order' => $request->sort_order]);

        return response()->json(['message' => 'Sort order updated successfully']);
    }
}
