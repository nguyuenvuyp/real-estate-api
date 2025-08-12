<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    // Danh sách bất động sản + filter + sort + pagination
    public function index(Request $request)
    {
        $query = Property::query();

        // Tìm kiếm theo title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo loại
        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Sắp xếp
        if ($request->has('sort_by') && $request->has('order')) {
            $query->orderBy($request->sort_by, $request->order);
        }

        return response()->json($query->paginate($request->get('per_page', 10)));
    }

    // Tạo mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:apartment,house,villa,office,land',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $property = Property::create($request->except('images'));

        // Upload nhiều ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'image_name' => $file->getClientOriginalName(),
                    'is_primary' => $index === 0
                ]);
            }
        }

        return response()->json($property, 201);
    }

    // Xem chi tiết
    public function show($id)
    {
        $property = Property::with('images')->findOrFail($id);
        return response()->json($property);
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $property->update($request->except('images'));

        // Nếu upload ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'image_name' => $file->getClientOriginalName()
                ]);
            }
        }

        return response()->json($property);
    }

    // Xóa mềm
    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}